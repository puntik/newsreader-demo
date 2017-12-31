<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Model\Entity\Category;
use App\Model\Entity\Feed;
use App\Model\Entity\Source;
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
		$feeds      = $this->loadFeeds($id);
		$categories = $this->categoryRepository->loadCategories();
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
		$sources = Source::whereCategoryId($categoryId)->get(['id'])->map(function ($item) {
			return $item['id'];
		})->toArray();

		return Feed::whereIn('source_id', $sources)
				   ->orderBy('published_at', 'desc')
				   ->paginate(self::PAGE_SIZE);
	}
}
