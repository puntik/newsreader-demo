<?php declare(strict_types = 1);

namespace App\Console\Commands;

use App\Model\Entity\Feed;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ElasticDataFill extends Command
{

	const CHUNK_SIZE = 100;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'elastic:data-fill {indexName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Simple elastic search filler';

	/** @var Client */
	private $esClient;

	public function __construct(Client $esClient)
	{
		$this->esClient = $esClient;

		parent::__construct();
	}

	public function handle()
	{
		$indexName = $this->argument('indexName');

		$progressBarSize = (int) Feed::count() / self::CHUNK_SIZE;
		$bar             = $this->output->createProgressBar($progressBarSize);
		$bar->setMessage(sprintf('Indexing data (chunk size: %d records)', self::CHUNK_SIZE));

		$bar->setFormat("%message%:\n %current%/%max% [%bar%] %percent:3s%% %remaining%");

		Feed::chunk(self::CHUNK_SIZE, function ($feeds) use ($indexName, $bar) {
			foreach ($feeds as $feed) {
				$this->indexFeed($feed, $indexName);
			}
			$bar->advance();
		});

		$bar->finish();
		$this->output->newLine();
	}

	private function indexFeed(Feed $feed, string $indexName)
	{
		$body = $feed->toSearchableArray();

		$this->esClient->index(
			[
				'index' => $indexName,
				'type'  => 'feed',
				'id'    => $feed->id,
				'body'  => $body,
			]
		);
	}
}
