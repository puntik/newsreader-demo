<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Model\Entity\Category;
use App\Model\Services\Category\EloquentCategoryRepository;

class HelloController extends Controller
{

	private const PAGE_SIZE = 24;

	/** @var EloquentCategoryRepository */
	private $categoryRepository;

	public function __construct(
		EloquentCategoryRepository $categoryRepository
	) {
		$this->categoryRepository = $categoryRepository;
	}

	public function __invoke(int $id, string $categoryName)
	{
		$categories = $this->categoryRepository->loadCategories();
		$category   = Category::find($id);
		$feeds      = $category->feeds()->orderByDesc('published_at')->paginate(self::PAGE_SIZE);

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
