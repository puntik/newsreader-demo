<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchResultsController extends Controller
{

	/** @var \Elasticsearch\Client */
	private $client;

	public function __construct(
		\Elasticsearch\Client $client
	) {
		$this->client = $client;
	}

	public function __invoke(Request $request)
	{
		$this->validate($request, [
			's' => 'required',
		]);

		$term = $request->input('s');

		$feeds = $this->getFeedsFromElastic($term);

		return view('searchResults.default', ['feeds' => $feeds]);
	}

	private function getFeedsFromElastic(string $search)
	{
		$body = [
			"size"    => 20,
			"sort"    => [
				[
					"publishedAt" => [
						"order" => "desc",
					],
				],
			],
			"_source" => ["id"],
			"query"   => [
				"match" => [
					"title" => $search,
				],
			],
		];

		$params = [
			'index' => 'a1',
			'type'  => 'feed',
			'body'  => $body,
		];

		$results = $this->client->search($params);
		$hits    = $results['hits']['hits'];

		$ids = [];

		foreach ($hits as $hit) {
			$ids[] = (int) $hit['_source']['id'];
		}

		return $this->loadFeeds($ids);
	}

	private function loadFeeds(array $ids)
	{
		// find all fields by category id
		return DB::table('feed')
				 ->join('source', 'source.id', '=', 'feed.source_id')
				 ->join('category', 'category.id', '=', 'source.category_id')
				 ->whereIn('feed.id', $ids)
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
						 DB::raw('HOUR(TIMEDIFF(now(), published_at)) AS age_hours'),
					 ]
				 )
				 ->orderBy('feed.published_at', 'desc')
				 ->get();
	}
}


