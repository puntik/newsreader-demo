<?php declare(strict_types = 1);

namespace App\Model\Services;

use App\Model\Entity\Feed;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class FeedFileParser
{

	public function createFromFile(int $sourceId, string $inputFile): void
	{
		$root = $this->getFeedRoot($inputFile);

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

	private function getFeedRoot(string $inputFile): \SimpleXMLElement
	{
		try {
			$content = file_get_contents($inputFile);
		} catch (\Throwable $e) {
			$message = sprintf('Problem with opening xml file %s. Maybe it is not found.', $inputFile);
			Log::error($message);

			// raise 404 exception
			throw new \InvalidArgumentException($message, Response::HTTP_NOT_FOUND);
		}

		$root = @simplexml_load_string($content);
		if ($root === false) {
			$message = sprintf("Problem with opening xml file %s. Is it a xml file?", $inputFile);
			Log::error($message);

			// raise 422 exception
			throw new \InvalidArgumentException($message, Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		return $root;
	}
}
