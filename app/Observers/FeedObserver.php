<?php declare(strict_types = 1);

namespace App\Observers;

use App\Model\Entity\Feed;
use App\Model\Entity\Tag;
use App\Model\Services\Elastic;

class FeedObserver
{

	/** @var Elastic */
	private $elastic;

	public function __construct(Elastic $elastic)
	{
		$this->elastic = $elastic;
	}

	public function saved(Feed $feed): void
	{
		$this->elastic->indexFeed($feed);
		$tagStrings = $this->elastic->percolateTags($feed);
		$tags       = Tag::whereIn('title', $tagStrings)->get(['id'])->map(function ($item) {
			return $item->id;
		})->toArray();
		$feed->tags()->sync($tags);
	}
}
