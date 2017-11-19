<?php

namespace App\Console\Commands;

use Elasticsearch\Client;
use Illuminate\Console\Command;

class ElasticCreate extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'elastic:create-index 
	{indexName : Name of a new index}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a new elastic search index.';

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

		$this->esClient->indices()->create(
			[
				'index' => $indexName,
				'body'  => [
					'settings' => $this->createSettings(),
					'mappings' => $this->createMapping(),
				],
			]
		);
	}

	private function createMapping()
	{
		return [
			'feed' => [
				'properties' => [
					'description' => [
						'type'     => 'text',
						'analyzer' => 'czech',
					],
					'title'       => [
						'type'     => 'text',
						'analyzer' => 'czech',
					],
					'createdAt'   => [
						'type'   => 'date',
						'format' => 'yyyy-MM-dd HH:mm||yyyy-MM-dd',
					],
					'publishedAt' => [
						'type'   => 'date',
						'format' => 'yyyy-MM-dd HH:mm||yyyy-MM-dd',
					],
					'sourceId'    => [
						'type' => 'short',
					],
				],
			],
		];
	}

	private function createSettings()
	{
		return [
			'number_of_shards'   => 8,
			'number_of_replicas' => 0,
			'analysis'           => [
				'analyzer' => [
					'czech' => [
						'tokenizer' => 'standard',
						'filter'    => [
							'lowercase',
							'czech_stemmer',
							'icu_folding',
						],
					],
				],
				'filter'   => [
					'czech_stemmer' => [
						'type'     => 'stemmer',
						'language' => 'czech',
					],
				],
			],
		];
	}
}
