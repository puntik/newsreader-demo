<?php

namespace App\Console\Commands;

use Elasticsearch\Client;
use Illuminate\Console\Command;

class ElasticDelete extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'elastic:delete-index {indexName}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete index.';

	/** @var Client */
	private $esClient;

	public function __construct(Client $esClient)
	{
		$this->esClient = $esClient;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$indexName = $this->argument('indexName');

		$this->esClient->indices()->delete([
			'index' => $indexName,
		]);

		$this->esClient->indices()->delete([
			'index' => $indexName . '_tags',
		]);
	}
}
