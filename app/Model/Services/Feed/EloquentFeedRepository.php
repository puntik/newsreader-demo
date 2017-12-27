<?php declare(strict_types = 1);

namespace App\Model\Services\Feed;

use App\Model\Entity\Feed;

class EloquentFeedRepository implements FeedRepository
{

	public function loadNewestFeeds(int $limit = 30)
	{
		return Feed::whereActive(true)
				   ->orderByDesc('published_at')
				   ->limit($limit)
				   ->get();
	}
}
