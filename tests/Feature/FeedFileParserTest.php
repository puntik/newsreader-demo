<?php

namespace Tests\Feature;

use App\Model\Services\FeedFileParser;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class FeedFileParserTest extends TestCase
{

	/** @test */
	public function itCanParseRssFile()
	{
		$this->markTestIncomplete();
	}

	/** @test */
	public function itCanParseAtomFile()
	{
		$this->markTestIncomplete();
	}

	/** @test */
	public function itReportsAnErrorWhenInputFileIsNotAValidXml()
	{
		// Given
		$parser      = new FeedFileParser();
		$inputString = __DIR__ . '/../data/invalid_xml_feed.xml';

		Log::shouldReceive('error')->once();
		$this->expectException(\InvalidArgumentException::class);

		// When and Then
		$parser->createFromFile(1, $inputString);
	}

	/** @test */
	public function itFailedWhenInputFileIsMissing()
	{
		// Given
		$parser      = new FeedFileParser();
		$inputString = __DIR__ . '/../data/missing_feel.xml';

		Log::shouldReceive('error')->once();
		$this->expectException(\InvalidArgumentException::class);

		// When and Then
		$parser->createFromFile(1, $inputString);
	}
}
