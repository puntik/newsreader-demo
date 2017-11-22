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
		$this->client->index(
			[
				'index' => $this->indexName,
				'type'  => 'feed',
				'id'    => $feed->id,
				'body'  => $feed->toSearchableArray(),
			]
		);
	}
}
