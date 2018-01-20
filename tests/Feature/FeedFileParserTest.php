<?php

namespace Tests\Feature;

use App\Model\Services\FeedFileParser;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FeedFileParserTest extends TestCase
{

	/**
	 * @test
	 * @dataProvider validRssFiles
	 */
	public function itCanParseRssFile(string $filename, int $expected)
	{
		// Given
		$parser      = new FeedFileParser();
		$inputString = $this->getDataPath($filename);

		// When
		$items = $parser->getItems($inputString);

		// Then
		$this->assertCount($expected, $items);
	}

	/**
	 * @test
	 * @dataProvider validRssFiles
	 */
	public function itCanParseAtomFile(string $filename, int $expected)
	{
		// Given
		$parser      = new FeedFileParser();
		$inputString = $this->getDataPath($filename);

		// When
		$items = $parser->getItems($inputString);

		// Then
		$this->assertCount($expected, $items);
	}

	/** @test */
	public function itReportsAnErrorWhenInputFileIsNotAValidXml()
	{
		// Given
		$parser      = new FeedFileParser();
		$inputString = $this->getDataPath('invalid_xml_feed.xml');

		Log::shouldReceive('error')->once();
		$this->expectException(\InvalidArgumentException::class);

		// When and Then
		$parser->getItems($inputString);
	}

	/** @test */
	public function itFailedWhenInputFileIsMissing()
	{
		// Given
		$parser      = new FeedFileParser();
		$inputString = $this->getDataPath('missing_feel.xml');

		Log::shouldReceive('error')->once();
		$this->expectException(\InvalidArgumentException::class);

		// When and Then
		$parser->getItems($inputString);
	}

	public function validRssFiles()
	{
		return [
			['valid_atom_feed.xml', 100],
		];
	}

	public function validAtomFiles()
	{
		return [
			['valid_rss_feed.xml', 30],
		];
	}

	private function getDataPath(string $file): string
	{
		return sprintf('%s/../data/%s', __DIR__, $file);
	}
}
