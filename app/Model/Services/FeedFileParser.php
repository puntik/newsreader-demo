<?php declare(strict_types = 1);

namespace App\Model\Services;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class FeedFileParser
{

	/**
	 * @param string $inputFile
	 *
	 * @return array
	 */
	public function getItems(string $inputFile): array
	{
		$root = $this->getFeedRoot($inputFile);

		if ($root->getName() === 'feed') {
			return $this->processAtom($root);
		}

		if ($root->getName() === 'rss') {
			return $this->processRss($root);
		}
	}

	private function processAtom(\SimpleXMLElement $root): array
	{
		$feeds = [];

		foreach ($root->entry as $item) {
			$publishedAt = Carbon::createFromFormat(Carbon::ATOM, $item->updated);
			$link        = (string) $item->link->attributes()->href;

			$feed = [
				'link'         => $link,
				'title'        => (string) $item->title,
				'description'  => (string) $item->summary,
				'published_at' => $publishedAt->format(Carbon::DEFAULT_TO_STRING_FORMAT),
			];

			$feeds[] = $feed;
		}

		return $feeds;
	}

	private function processRss(\SimpleXMLElement $root): array
	{
		$feeds = [];

		foreach ($root->channel->item as $item) {
			$publishedAt = Carbon::createFromFormat(Carbon::RSS, $item->pubDate);

			$feed = [
				'link'         => (string) $item->link,
				'title'        => (string) $item->title,
				'description'  => (string) $item->description,
				'published_at' => $publishedAt->format(Carbon::DEFAULT_TO_STRING_FORMAT),
			];

			$feeds[] = $feed;
		}

		return $feeds;
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
