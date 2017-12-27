<?php declare(strict_types = 1);

namespace App\Model\Services\Feed;

use App\Model\Entity\Feed;

interface FeedRepository
{

	/**
	 * @param int $limit
	 *
	 * @return Feed[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function loadNewestFeeds(int $limit = 30);
}
