<?php

namespace App\Http\Controllers;

use App\Model\Services\Category\EloquentCategoryRepository;

class WelcomeController extends Controller
{

	/** @var EloquentCategoryRepository */
	private $categoryRepository;

	public function __construct(
		EloquentCategoryRepository $categoryRepository
	) {
		$this->categoryRepository = $categoryRepository;
	}

	public function __invoke()
	{
		$categories = $this->categoryRepository->loadCategories();

		return view(
			'welcome',
			[
				'categories' => $categories,
			]
		);
	}
}
