<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Model\Entity\Category;
use App\Model\Services\Category\EloquentCategoryRepository;

class HelloController extends Controller
{

	/** @var EloquentCategoryRepository */
	private $categoryRepository;

	/** @var int */
	private $perPage;

	public function __construct(
		EloquentCategoryRepository $categoryRepository
	) {
		$this->categoryRepository = $categoryRepository;
		$this->perPage            = (int) env('NEWSREADER_FEEDS_PER_PAGE', 24);
	}

	public function __invoke(int $id, string $categoryName)
	{
		$categories = $this->categoryRepository->loadCategories();
		$category   = Category::find($id);
		$feeds      = $category->feeds()->orderByDesc('published_at')->paginate($this->perPage);

		$flags = [
			'cs' => 'cz',
			'cz' => 'cz',
			'en' => 'gb',
		];

		return view('hello.default',
			[
				'feeds'      => $feeds,
				'flags'      => $flags,
				'categories' => $categories,
				'category'   => $category,
			]
		);
	}
}
