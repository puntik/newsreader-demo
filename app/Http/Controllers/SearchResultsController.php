<?php

namespace App\Http\Controllers;

use App\Model\Entity\Feed;
use Illuminate\Http\Request;

class SearchResultsController extends Controller
{

	/** @var int */
	private $perPage = 8;

	public function __invoke(Request $request)
	{
		$this->validate($request, [
			'q' => 'required',
		]);

		$term = $request->input('q');

		$feeds = Feed::search($term)->paginate($this->perPage);

		return view('searchResults.default', [
			'feeds' => $feeds,
			'term'  => $term,
		]);
	}
}
