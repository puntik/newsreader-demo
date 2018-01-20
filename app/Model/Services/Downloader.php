<?php declare(strict_types = 1);

namespace App\Model\Services;

use App\Model\Entity\Feed;
use App\Model\Entity\Source;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Downloader
{

	private const ERROR_NUMBER    = 5;
	private const FEED_SOURCE_DIR = 'feed_source';
	private const WARNING_NUMBER  = 2;

	/** @var Client */
	private $guzzleClient;

	/** @var FeedFileParser */
	private $parser;

	public function __construct(Client $guzzleClient)
	{
		$this->guzzleClient = $guzzleClient;
		$this->parser       = new FeedFileParser();
	}

	public function processSources(): void
	{
		$sources = Source::whereActive(true)->get();

		foreach ($sources as $source) {
			Log::debug(sprintf('Downloading #%d %s', $source->id, $source->title));
			$this->processSource($source);
		}
	}

	public function processSource(Source $source): void
	{
		$rssFile  = sprintf('%s/%d.xml', Storage::path(self::FEED_SOURCE_DIR), $source->id);
		$cacheKey = sprintf('source.failed.%d', $source->id);

		$failedAttempts = Cache::rememberForever($cacheKey, function () { return 0; });

		try {
			$response = $this->guzzleClient->get($source->url, [
				'save_to' => $rssFile,
				'timeout' => 5,
			]);

			if ($response->getStatusCode() !== Response::HTTP_OK) {
				throw new \InvalidArgumentException('Unexpected response code');
			}

			$items   = $this->parser->getItems($rssFile);
			$cleaner = FeedCleaner::getInstance();

			foreach ($items as $item) {
				try {
					Feed::firstOrCreate(
						[
							'link' => $item['link'],
						], [
							'title'        => $item['title'],
							'description'  => $cleaner->run($item['description']),
							'source_id'    => $source->id,
							'published_at' => new Carbon($item['published_at']),
						]
					);
				} catch (\Illuminate\Database\QueryException $e) {
					Log::error($e->getMessage());
				}
			}

			// reset cache when success
			$this->resetCache($cacheKey);
		} catch (\Throwable $e) {
			$failedAttempts = Cache::increment($cacheKey);
		}

		if ($failedAttempts > self::WARNING_NUMBER) {
			Log::warning(sprintf('Source #%d "%s" is about to be disabled. Number of failed attempts is %d',
					$source->id,
					$source->title,
					$failedAttempts
				)
			);
		}

		if ($failedAttempts > self::ERROR_NUMBER) {
			$source->disable()->save();
			Log::error(sprintf('Source #%d "%s" was disabled.', $source->id, $source->title));
		}
	}

	private function resetCache(string $key): void
	{
		$failedAttempts = Cache::get($key);
		if ($failedAttempts === 0) {
			return;
		}

		Log::info(sprintf(
				'Resetting number of attempts for key #%s, number of failed attempts %d.',
				$key,
				$failedAttempts
			)
		);
		Cache::put($key, 0);
	}
}
