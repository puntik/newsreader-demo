<?php

namespace App\Http\Controllers;

use App\Model\Services\Category\EloquentCategoryRepository;
use App\Model\Services\Feed\EloquentFeedRepository;

class WelcomeController extends Controller
{

	/** @var EloquentCategoryRepository */
	private $categoryRepository;

	/** @var EloquentFeedRepository */
	private $feedRepository;

	public function __construct(
		EloquentCategoryRepository $categoryRepository,
		EloquentFeedRepository $feedRepository
	) {
		$this->categoryRepository = $categoryRepository;
		$this->feedRepository     = $feedRepository;
	}

	public function __invoke()
	{
		$categories  = $this->categoryRepository->loadCategories();
		$newestFeeds = $this->feedRepository->loadNewestFeeds(15);

		return view(
			'welcome',
			[
				'categories'  => $categories,
				'newestFeeds' => $newestFeeds,
			]
		);
	}
}
