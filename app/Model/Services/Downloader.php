<?php declare(strict_types = 1);

namespace App\Model\Services;

use App\Model\Entity\Feed;
use App\Model\Entity\Source;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
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

			if ($response->getStatusCode() !== Response::HTTP_OK) {
				throw new \InvalidArgumentException('Unexpected response code');
			}

			$this->createFeedsFromFile($source, $rssFile);
		} catch (\GuzzleHttp\Exception\RequestException $e) {
			Log::error(sprintf('Downloading source [%s, id: %d] failed.', $source->title, $source->id));
			$source->disable()->save();
		} catch (\Throwable $e) {
			Log::error($e->getMessage());
			$source->disable()->save();
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
