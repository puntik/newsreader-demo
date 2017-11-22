<?php declare(strict_types = 1);

namespace App\Console\Commands\Reader;

use App\Model\Entity\Feed;
use App\Model\Services\Elastic;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

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
		$sources = \App\Model\Entity\Source::where('active', true)->limit(20)->get();

		// 2 .. in cycle - download files to parse
		foreach ($sources as $source) {
			$this->info(sprintf('Downloading #%d', $source->id));

			$rssFile = sprintf('%s/%d.xml', Storage::path($feedSourcePath), $source->id);

			try {
				$response = $this->guzzleClient->get($source->url, [
					'save_to' => $rssFile,
					'timeout' => 5,
				]);

				if ($response->getStatusCode() !== 200) {
					throw new \InvalidArgumentException('Unexpected response code');
				}

				// 3 .. parse file and save it to db - including indexing into elastic search
				$root = simplexml_load_file($rssFile);
				if ($root === false) {
					$msg = sprintf("Problem with opening xml file %s.", $rssFile);
				}

				$items = $root->xpath('//rss/channel/item');

				foreach ($items as $item) {
					$publishedAt = new Carbon($item->pubDate);

					$feed               = new Feed();
					$feed->title        = (string) $item->title;
					$feed->link         = (string) $item->link;
					$feed->description  = (string) $item->description;
					$feed->published_at = $publishedAt;
					$feed->active       = true;

					$feed->source()->associate($source);

					// check if record already exist
					// @todo - index on link column
					$old = Feed::where(['link' => $item->link])->first();
					if ($old === null) {
						$feed->save();

						// indexing to elastic search
						// @todo use scout or saved method
						$this->elastic->indexFeed($feed);
					} else {
						$this->warn(sprintf('Found old feed with #%d', $old->id));
					}
				};
			} catch (\GuzzleHttp\Exception\RequestException $e) {
				$this->error(sprintf('Downloading source [%s, id: %d] failed.', $source->title, $source->id));
				$source->active = false;
				$source->save();
			} catch (\Throwable $e) {
				$this->error($e->getTraceAsString());
				$this->error($e->getMessage());
				$source->active = false;
				$source->save();
			}
		}
	}
}
