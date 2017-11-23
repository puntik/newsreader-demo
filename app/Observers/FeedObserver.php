<?php declare(strict_types = 1);

namespace App\Observers;

use App\Model\Entity\Feed;
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
	}
}
