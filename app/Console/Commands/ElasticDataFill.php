<?php declare(strict_types = 1);

namespace App\Console\Commands;

use App\Model\Entity\Feed;
use App\Model\Entity\Tag;
use App\Model\Services\Elastic;
use Illuminate\Console\Command;

class ElasticDataFill extends Command
{

	private const CHUNK_SIZE = 100;

	/* @var string */
	protected $signature = 'elastic:data-fill';

	/** @var string */
	protected $description = 'Simple elastic search filler';

	/** @var Elastic */
	private $elastic;

	public function __construct(
		Elastic $elastic
	) {
		parent::__construct();

		$this->elastic = $elastic;
	}

	public function handle()
	{
		$progressBarSize = (int) Feed::count() / self::CHUNK_SIZE;
		$bar             = $this->output->createProgressBar($progressBarSize);

		$this->output->writeln('Indexing saved queries for tagging');
		$this->elastic->refreshTags();
		$this->elastic->indexTaggedQueries();

		$bar->setMessage(sprintf('Indexing data (chunk size: %d records)', self::CHUNK_SIZE));

		$bar->setFormat("%message%:\n %current%/%max% [%bar%] %percent:3s%% %remaining%");
		Feed::chunk(self::CHUNK_SIZE, function ($feeds) use ($bar) {
			/** @var Feed $feed */
			foreach ($feeds as $feed) {
				$this->elastic->indexFeed($feed);
				$tagStrings = $this->elastic->percolateTags($feed);
				$tags       = Tag::whereIn('title', $tagStrings)->get(['id'])->map(function ($item) {
					return $item->id;
				})->toArray();
				$feed->tags()->sync($tags);
			}
			$bar->advance();
		});

		$bar->finish();
		$this->output->newLine();
	}
}
