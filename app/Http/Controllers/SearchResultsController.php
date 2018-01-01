<?php

namespace App\Http\Controllers;

use App\Model\Entity\Feed;
use Illuminate\Http\Request;

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
			'q' => 'required',
		]);

		$term = $request->input('q');

		$feeds = Feed::search($term)->paginate(10);

		return view('searchResults.default', [
			'feeds' => $feeds,
			'term'  => $term,
		]);
	}
}
