<?php declare(strict_types = 1);

namespace App\Model\Services;

use App\Model\Entity\Feed;
use Elasticsearch\Client;

class Elastic
{

	/** @var string */
	private $indexName;

	/** @var Client */
	private $client;

	public function __construct(Client $client)
	{
		$this->client    = $client;
		$this->indexName = env('ELASTIC_INDEX', 'a1');
	}

	public function indexFeed(Feed $feed)
	{
		$this->client->index([
			'index' => $this->indexName,
			'type'  => 'feed',
			'id'    => $feed->id,
			'body'  => $feed->toSearchableArray(),
		]);
	}

	public function percolateTags(Feed $feed)
	{
		$index = sprintf('%s_tags', $this->indexName);

		$body = [
			'query' => [
				'percolate' => [
					'field'         => 'query',
					'document_type' => 'doctype',
					'index'         => $this->indexName,
					'type'          => 'feed',
					'id'            => (string) $feed->id,
				],
			],
		];

		$response = $this->client->search([
			'index'   => $index,
			'type'    => 'tags',
			'_source' => 'meta.*',
			'body'    => $body,
		]);

		$hits = $response['hits']['hits'];

		if (count($hits) === 0) {
			return [];
		}

		$tags = [];
		foreach ($hits as $hit) {
			$tags = array_merge($tags, $hit['_source']['meta']['tags']);
		}

		return array_sort(array_unique($tags));
	}
}
