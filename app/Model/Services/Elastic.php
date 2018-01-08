<?php declare(strict_types = 1);

namespace App\Model\Services;

use App\Model\Entity\Feed;
use App\Model\Entity\Tag;
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

	public function indexTaggedQueries()
	{
		$index = sprintf('%s_tags', $this->indexName);
		$input = $this->loadSearchableTags();

		foreach ($input as $term => $tags) {


			$indexedData = [
				'query' => [
					'multi_match' => [
						'query'  => $term,
						'fields' => [
							'title',
							'description',
						],
					],
				],
				'meta'  => [
					'tags' => $tags,
				],
			];

			$this->client->index([
				'index' => $index,
				'type'  => 'tags',
				'body'  => $indexedData,
			]);
		}
	}

	public function refreshTags(): void
	{
		$input = $this->loadSearchableTags();
		$tags  = array_unique(array_flatten(array_values($input)));

		foreach ($tags as $tag) {
			Tag::firstOrCreate(['title' => $tag]);
		}
	}

	private function loadSearchableTags(): array
	{
		return [
			'php' => ['php'],

			'laravel'  => ['php', 'laravel'],
			'lumen'    => ['php', 'laravel', 'lumen'],
			'eloquent' => ['php', 'laravel', 'eloquent', 'db'],
			'blade'    => ['php', 'laravel', 'blade'],
			'scout'    => ['php', 'laravel', 'scout'],
			'tinker'   => ['php', 'laravel', 'tinker'],

			'mysql'      => ['db', 'mysql'],
			'postgresql' => ['db', 'postgresql'],
			'redis'      => ['db', 'redis'],

			'nette' => ['php', 'nette'],
			'latte' => ['php', 'nette', 'latte'],
			'tracy' => ['php', 'nette', 'tracy'],

			'elasticsearch' => ['elasticsearch'],
			'logstash'      => ['elasticsearch', 'logstash'],
			'kibana'        => ['elasticsearch', 'kibana'],

			'symfony' => ['php', 'symfony'],
			'twig'    => ['php', 'symfony', 'twig'],
		];
	}
}
