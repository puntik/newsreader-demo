<?php declare(strict_types = 1);

namespace App\Console\Commands\Reader;

use App\Model\Entity\Feed;
use App\Model\Entity\Source;
use App\Model\Services\Elastic;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\OutputInterface;

class Dowloader extends Command
{

	/** @var string */
	protected $signature = 'newsreader:load-feeds';

	/** @var string */
	protected $description = 'Load all feeds and save them to database';

	/** @var  Client */
	private $guzzleClient;

	/** @var Elastic */
	private $elastic;

	public function __construct(
		Elastic $elastic
	) {
		parent::__construct();

		$this->elastic      = $elastic;
		$this->guzzleClient = new Client();
	}

	public function handle()
	{
		// @todo welcome to spaghetti code
		$feedSourcePath = 'feed_source';

		// 1 .. from db load all Sources to download
		$sources = Source::where('active', true)->get();

		// 2 .. in cycle - download files to parse
		foreach ($sources as $source) {
			// @todo remove me or log me in logger way
			$this->info(
				sprintf('Downloading #%d %s', $source->id, $source->title),
				OutputInterface::VERBOSITY_VERBOSE
			);

			$rssFile = sprintf('%s/%d.xml', Storage::path($feedSourcePath), $source->id);

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
				$source->active = false;
				$source->save();
			} catch (\Throwable $e) {
				Log::error($e->getMessage());
				$source->active = false;
				$source->save();
			}
		}
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
