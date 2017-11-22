<?php declare(strict_types = 1);

namespace App\Console\Commands;

use App\Model\Entity\Feed;
use App\Model\Services\Elastic;
use Illuminate\Console\Command;

class ElasticDataFill extends Command
{

	const CHUNK_SIZE = 100;

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
		$bar->setMessage(sprintf('Indexing data (chunk size: %d records)', self::CHUNK_SIZE));

		$bar->setFormat("%message%:\n %current%/%max% [%bar%] %percent:3s%% %remaining%");

		Feed::chunk(self::CHUNK_SIZE, function ($feeds) use ($bar) {
			foreach ($feeds as $feed) {
				$this->elastic->indexFeed($feed);
			}
			$bar->advance();
		});

		$bar->finish();
		$this->output->newLine();
	}
}
