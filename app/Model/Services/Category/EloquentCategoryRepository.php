<?php declare(strict_types = 1);

namespace App\Model\Services\Category;

use App\Model\Entity\Category;
use Elasticsearch\Client;

class EloquentCategoryRepository implements CategoryRepository
{

	/** @var Client */
	private $esClient;

	public function __construct(Client $esClient)
	{
		$this->esClient = $esClient;
	}

	public function loadCategories()
	{
		// ask elastic search for statistics
		$categoriesInfo = $this->loadCategoriesInfo();

		// filter categories
		$categories = Category::all();
		$categories = $categories->filter(function ($category) use ($categoriesInfo) {
			return array_key_exists($category->id, $categoriesInfo);
		});

		// improve categories
		$categories->map(function ($category) use ($categoriesInfo) {
			$category->total     = $categoriesInfo[$category->id]['total'] ?? 0;
			$category->last_week = $categoriesInfo[$category->id]['last_week'] ?? 0;
			$category->today     = $categoriesInfo[$category->id]['today'] ?? 0;

			return $category;
		});

		return $categories;
	}

	private function loadCategoriesInfo()
	{
		$aggregationJson =
			'
				{
					"by_categories": {
					  "terms": {
						"order": {
						  "_count": "desc"
						},
						"field": "categoryId",
						"size": 20
					  },
					  "aggs": {
						"by_categories_intervals": {
						  "range": {
							"field": "publishedAt",
							"ranges": [
							  {
								"key": "last_week",
								"from": "now-1w",
								"to": "now"
							  },
							  {
								"key": "today",
								"from": "now/d",
								"to": "now"
							  }
							]
						  }
						}
					  }
					},
					"by_sources": {
					  "terms": {
						"order": {
						  "_count": "desc"
						},
						"field": "sourceId",
						"size": 10
					  },
					  "aggs": {
						"by_sources_intervals": {
						  "range": {
							"field": "publishedAt",
							"ranges": [
							  {
								"key": "last_week",
								"from": "now-1w",
								"to": "now"
							  },
							  {
								"key": "today",
								"from": "now/d",
								"to": "now"
							  }
							]
						  }
						}
					  }
					}
				}
			';

		$json =
			'
				{
					  "size": 0,
					  "_source": [
						"title",
						"publishAt",
						"sourceId",
						"cateogoryId"
				    ]
				}
			';

		$query         = json_decode($json, true);
		$query['aggs'] = json_decode($aggregationJson);

		$response = $this->esClient->search(
			[
				'index' => 'a1',
				'type'  => 'feed',
				'body'  => $query,
			]
		);

		$byCategories = $response['aggregations']['by_categories']['buckets'];
		$categoryInfo = [];

		foreach ($byCategories as $categoryBucket) {
			$item['id']    = $categoryBucket['key'];
			$item['total'] = $categoryBucket['doc_count'];

			$intervalBuckets = $categoryBucket['by_categories_intervals']['buckets'];

			foreach ($intervalBuckets as $intervalBucket) {
				$item[$intervalBucket['key']] = $intervalBucket['doc_count'];
			}

			$categoryInfo[$categoryBucket['key']] = $item;
		}

		return $categoryInfo;
	}
}
