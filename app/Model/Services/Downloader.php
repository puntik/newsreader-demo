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

	private const FEED_SOURCE_DIR = 'feed_source';

	/** @var Client */
	private $guzzleClient;

	public function __construct(Client $guzzleClient)
	{
		$this->guzzleClient = $guzzleClient;
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

		$failedAttemps = Cache::rememberForever($cacheKey, function () { return 0; });

		try {
			$response = $this->guzzleClient->get($source->url, [
				'save_to' => $rssFile,
				'timeout' => 5,
			]);

			if ($response->getStatusCode() !== Response::HTTP_OK) {
				throw new \InvalidArgumentException('Unexpected response code');
			}

			$this->createFeedsFromFile($source, $rssFile);

			// reseting cache when success
			Cache::put($cacheKey, 0);
		} catch (\Throwable $e) {
			$failedAttemps = Cache::increment($cacheKey);
		}

		if ($failedAttemps > 2) {
			Log::warning(sprintf('Source #%d "%s" is about to be disabled. Number of parse attemp: %s',
					$source->id,
					$source->title,
					$failedAttemps
				)
			);
		}

		if ($failedAttemps > 5) {
			$source->disable()->save();
			Log::error(sprintf('Source #%d "%s" was disabled.', $source->id, $source->title));
		}
	}

	private function createFeedsFromFile(Source $source, string $rssFile): void
	{
		$root = simplexml_load_file($rssFile);
		if ($root === false) {
			Log::error(sprintf("Problem with opening xml file %s.", $rssFile));

			return;
		}

		$items = $root->xpath('//rss/channel/item');

		foreach ($items as $item) {

			Feed::updateOrCreate(
				[
					'link' => (string) $item->link,
				], [
					'title'        => (string) $item->title,
					'description'  => (string) $item->description,
					'source_id'    => $source->id,
					'published_at' => new Carbon($item->pubDate),
				]
			);
		}
	}
}
