<?php

namespace Tests\Unit;

use App\Model\Services\FeedCleaner;
use Tests\TestCase;

class FeedCleanerTest extends TestCase
{

	/**
	 * @test
	 * @dataProvider dataProvider
	 */
	public function itCanCleanInputString(string $input, string $expected)
	{
		// Given
		$cleaner = FeedCleaner::getInstance();

		// When
		$cleaned = $cleaner->run($input);

		// Then
		$this->assertEquals($expected, $cleaned);
	}

	/**
	 * @test
	 * @dataProvider dataProvider
	 */
	public function itCanCleanInputArray(string $input, string $expected)
	{
		// Given
		$tested['text'] = $input;
		$cleaner        = FeedCleaner::getInstance();

		// When
		$cleaned = $cleaner->run($tested);

		// Then
		$this->assertEquals($expected, $cleaned['text']);
	}

	public function dataProvider()
	{
		return [
			['<b>hello</b>', 'hello'],
			['<b>hello   </b>', 'hello'],
			['    <b>hello</b>    ', 'hello'],
		];
	}
}
