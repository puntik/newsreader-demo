<?php declare(strict_types = 1);

namespace App\Model\Services\ElasticScout;

use Elasticsearch\Client;

class ElasticSearcher
{

	/** @var Client */
	private $client;

	/** @var int */
	private $page;

	/** @var int */
	private $size;

	public function __construct(Client $client)
	{
		$this->client = $client;

		$this->page(1);
		$this->size(10);
		$this->index = env('ELASTIC_INDEX', 'index');
	}

	public function page(int $page): self
	{
		$this->page = $page;

		return $this;
	}

	public function size(int $size): self
	{
		$this->size = $size;

		return $this;
	}

	private function getFrom(): int
	{
		return $this->page === 1 ? 0 : ($this->page - 1) * $this->size;
	}

	public function get(string $search)
	{
		$body = [
			'from'    => $this->getFrom(),
			'size'    => $this->size,
			'_source' => ['id'],
			'query'   => [
				'multi_match' => [
					'query'  => $search,
					'fields' => [
						'title',
						'description',
					],
				],
			],
		];

		$params = [
			'index' => $this->index,
			'type'  => 'feed',
			'body'  => $body,
		];

		return $this->client->search($params);
	}
}
