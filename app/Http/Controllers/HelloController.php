<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Model\Entity\Category;
use App\Model\Services\Category\EloquentCategoryRepository;
use Illuminate\Support\Facades\DB;

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
						 DB::raw('HOUR(TIMEDIFF(now(), published_at)) AS age_hours'),
					 ]
				 )
				 ->orderBy('feed.published_at', 'desc')
				 ->paginate(self::PAGE_SIZE);
	}
}
