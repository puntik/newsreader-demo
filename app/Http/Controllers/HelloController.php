<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Model\Entity\Category;
use Elasticsearch\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class HelloController extends Controller
{

	const PAGE_SIZE = 24;

	/** @var Client */
	private $client;

	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	public function __invoke(int $id)
	{
		$name       = Request::query('name', 'Abetzi');
		$feeds      = $this->loadFeeds($id);
		$categories = $this->loadCategories();
		$category   = Category::find($id);

		$flags = [
			'cs' => 'cz',
			'cz' => 'cz',
			'en' => 'gb',
		];

		if (count($category) === 0) {
			return 'nothing to show';
		}

		return view('hello.default',
			[
				'feeds'      => $feeds,
				'flags'      => $flags,
				'categories' => $categories,
				'category'   => $category,
			]
		);
	}

	private function loadFeeds(int $categoryId)
	{
		// find all fields by category id
		return DB::table('feed')
				 ->join('source', 'source.id', '=', 'feed.source_id')
				 ->join('category', 'category.id', '=', 'source.category_id')
				 ->where('source.category_id', '=', $categoryId)
				 ->select(
					 [
						 'feed.title',
						 'feed.id',
						 'feed.description',
						 'feed.link',
						 'category.id AS category_id',
						 'category.title AS category',
						 'source.title AS source',
						 'published_at',
						 'source.language',
						 DB::raw('HOUR(TIMEDIFF(now(), published_at)) AS age_hours')
					 ]
				 )
				 ->orderBy('id', 'desc')
				 ->paginate(self::PAGE_SIZE);
	}

	private function loadCategories()
	{
		// ask elastic search for statistics
		$categoriesInfo = $this->loadCateogriesInfo();

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

	private function loadCateogriesInfo()
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
							"field": "createdAt",
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
							"field": "createdAt",
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
						"createdAt",
						"sourceId",
						"cateogoryId"
				    ]
				}
			';

		$query         = json_decode($json, true);
		$query['aggs'] = json_decode($aggregationJson);

		$response = $this->client->search(
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
