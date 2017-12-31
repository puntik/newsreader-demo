<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{

	private const SEARCH_TERM = 'php';

	/**
	 *
	 */
	public function basicTest()
	{
		$feeds = \App\Model\Entity\Feed::search(self::SEARCH_TERM)->get();

		$this->assertCount(24, $feeds);
	}

	/**
	 * @test
	 */
	public function paginateFirstPage()
	{
		$feeds = \App\Model\Entity\Feed::search(self::SEARCH_TERM)->paginate(10);

		// total pages
		$this->assertEquals(24, $feeds->total());

		// items on current page - first in this case
		$this->assertCount(10, $feeds);

		// last page .. third
		$this->assertEquals(3, $feeds->lastPage());
	}

	/**
	 * @test
	 */
	public function paginateLastPage()
	{
		$feeds = \App\Model\Entity\Feed::search(self::SEARCH_TERM)->paginate(10, 'page', 3);

		// items on current page - third in this case
		$this->assertCount(4, $feeds);
	}
}
