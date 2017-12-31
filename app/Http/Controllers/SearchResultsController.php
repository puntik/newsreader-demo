<?php

namespace App\Http\Controllers;

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

		// $feeds = $this->getFeedsFromElastic($term);
		$feeds = \App\Model\Entity\Feed::search($term)->get();

		return view('searchResults.default', [
			'feeds' => $feeds,
			'term'  => $term,
		]);
	}
}


