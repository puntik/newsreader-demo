<?php declare(strict_types = 1);

namespace App\Model\Services\ElasticScout;

use App\Model\Entity\Feed;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Scout\Builder;
use Laravel\Scout\Engines\Engine;

class ElasticEngine extends Engine
{

	/** @var ElasticSearcher */
	private $elasticSearcher;

	public function __construct(Client $client)
	{
		$this->elasticSearcher = new ElasticSearcher($client);
	}

	public function update($models): void
	{
		// TODO: Implement update() method.
	}

	public function delete($models): void
	{
		// TODO: Implement delete() method.
	}

	/**
	 * Perform the given search on the engine.
	 *
	 * @param  Builder $builder
	 *
	 * @return mixed
	 */
	public function search(Builder $builder)
	{
		return $this->elasticSearcher->get($builder->query);
	}

	/**
	 * Perform the given search on the engine.
	 *
	 * @param  Builder $builder
	 * @param  int     $perPage
	 * @param  int     $page
	 *
	 * @return mixed
	 */
	public function paginate(Builder $builder, $perPage, $page)
	{
		return $this->elasticSearcher
			->size($perPage)
			->page($page)
			->get($builder->query);
	}

	public function mapIds($results): array
	{
		return array_map(function ($item): int {
			return $item['_source']['id'];
		}, $results['hits']['hits']);
	}

	public function map($results, $model): Collection
	{
		return Feed::whereIn(
			'id',
			$this->mapIds($results)
		)->orderBy('published_at', 'desc')->get();
	}

	public function getTotalCount($results): int
	{
		return $results['hits']['total'];
	}
}
