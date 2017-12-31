<?php declare(strict_types = 1);

namespace App\Model\Services\ElasticScout;

use App\Model\Entity\Feed;
use Elasticsearch\Client;
use Laravel\Scout\Engines\Engine;

class ElasticEngine extends Engine
{

	/** @var Client */
	private $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * Update the given model in the index.
	 *
	 * @param  \Illuminate\Database\Eloquent\Collection $models
	 *
	 * @return void
	 */
	public function update($models)
	{
		// TODO: Implement update() method.
	}

	/**
	 * Remove the given model from the index.
	 *
	 * @param  \Illuminate\Database\Eloquent\Collection $models
	 *
	 * @return void
	 */
	public function delete($models)
	{
		// TODO: Implement delete() method.
	}

	/**
	 * Perform the given search on the engine.
	 *
	 * @param  \Laravel\Scout\Builder $builder
	 *
	 * @return mixed
	 */
	public function search(\Laravel\Scout\Builder $builder)
	{
		return $this->getIdsFromElastic($builder->query);
	}

	/**
	 * Perform the given search on the engine.
	 *
	 * @param  \Laravel\Scout\Builder $builder
	 * @param  int                    $perPage
	 * @param  int                    $page
	 *
	 * @return mixed
	 */
	public function paginate(\Laravel\Scout\Builder $builder, $perPage, $page)
	{
		return $this->getIdsFromElastic(
			$builder->query,
			$perPage,
			$page
		);
	}

	/**
	 * Pluck and return the primary keys of the given results.
	 *
	 * @param  mixed $results
	 *
	 * @return \Illuminate\Support\Collection
	 */
	public function mapIds($results)
	{
		return array_map(function ($item): int {
			return $item['_source']['id'];
		}, $results['hits']['hits']);
	}

	/**
	 * Map the given results to instances of the given model.
	 *
	 * @param  mixed                               $results
	 * @param  \Illuminate\Database\Eloquent\Model $model
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function map($results, $model)
	{
		return Feed::whereIn(
			'id',
			$this->mapIds($results)
		)->orderBy('published_at', 'desc')->get();
	}

	/**
	 * Get the total count from a raw result returned by the engine.
	 *
	 * @param  mixed $results
	 *
	 * @return int
	 */
	public function getTotalCount($results): int
	{
		return $results['hits']['total'];
	}

	private function getIdsFromElastic(
		string $search,
		int $size = 10,
		int $page = 1
	) {

		$from = $page === 1 ? 0 : ($page - 1) * 10;

		$body = [
			'from'    => $from,
			'size'    => $size,
			'sort'    => [
				[
					'publishedAt' => [
						'order' => 'desc',
					],
				],
			],
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
			'index' => 'a1',
			'type'  => 'feed',
			'body'  => $body,
		];

		return $this->client->search($params);
	}
}
