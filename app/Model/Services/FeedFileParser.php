<?php declare(strict_types = 1);

namespace App\Model\Services;

use App\Model\Entity\Feed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class FeedFileParser
{

	public function createFromFile(int $sourceId, string $inputFile): void
	{
		$content = file_get_contents($inputFile);
		$root    = simplexml_load_string($content);
		if ($root === false) {
			Log::error(sprintf("Problem with opening xml file %s.", $inputFile));

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
					'source_id'    => $sourceId,
					'published_at' => new Carbon($item->pubDate),
				]
			);
		}
	}
}
