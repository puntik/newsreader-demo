<?php declare(strict_types = 1);

namespace App\Model\Services;

use App\Model\Entity\Feed;
use App\Model\Entity\Source;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Downloader
{

	private const FEED_SOURCE_DIR = 'feed_source';

	/** @var Client */
	private $guzzleClient;

	public function __construct()
	{
		$this->guzzleClient = new Client();
	}

	public function processSources(): void
	{
		$sources = Source::where('active', true)->get();

		foreach ($sources as $source) {
			Log::info(sprintf('Downloading #%d %s', $source->id, $source->title));
			$this->processSource($source);
		}
	}

	public function processSource(Source $source): void
	{
		$rssFile = sprintf('%s/%d.xml', Storage::path(self::FEED_SOURCE_DIR), $source->id);

		try {
			$response = $this->guzzleClient->get($source->url, [
				'save_to' => $rssFile,
				'timeout' => 5,
			]);

			if ($response->getStatusCode() !== 200) {
				throw new \InvalidArgumentException('Unexpected response code');
			}

			$feeds = $this->createFeedsFromFile($source, $rssFile);

			foreach ($feeds as $feed) {
				$feed->save();    // saving and indexing (in observer)
			};
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			Log::error(sprintf('Downloading source [%s, id: %d] failed.', $source->title, $source->id));
			$this->inactiveSource($source);
		} catch (\Throwable $e) {
			Log::error($e->getMessage());
			$this->inactiveSource($source);
		}
	}

	private function inactiveSource(Source $source)
	{
		$source->active = false;
		$source->save();
	}

	private function createFeedsFromFile(Source $source, string $rssFile): array
	{
		$feeds = [];
		$root  = simplexml_load_file($rssFile);
		if ($root === false) {
			Log::error(sprintf("Problem with opening xml file %s.", $rssFile));

			return $feeds;
		}

		$items = $root->xpath('//rss/channel/item');

		foreach ($items as $item) {
			$old = Feed::where(['link' => $item->link])->first();

			if ($old !== null) {
				continue;
			}

			$publishedAt = new Carbon($item->pubDate);

			$feed               = new Feed();
			$feed->title        = (string) $item->title;
			$feed->link         = (string) $item->link;
			$feed->description  = (string) $item->description;
			$feed->published_at = $publishedAt;
			$feed->active       = true;

			$feed->source()->associate($source);

			$feeds[] = $feed;
		}

		return $feeds;
	}
}
