<?php

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Scout\Searchable;

class Feed extends Model
{

	use Searchable;

	public const FEED_INDEX = 'a1';

	protected $table = 'feed';

	protected $fillable = [
		'title',
		'link',
		'description',
		'source_id',
		'published_at',
		'active',
	];

	protected $dates = [
		'created_at',
		'published_at',
	];

	public function source()
	{
		return $this->belongsTo(Source::class);
	}

	public function searchableAs(): string
	{
		return self::FEED_INDEX;
	}

	public function ageInHours(): int
	{
		$now         = new Carbon();
		$publishedAt = new Carbon($this->published_at);

		return $now->diffInHours($publishedAt);
	}

	public function toSearchableArray()
	{
		return [
			'id'          => $this->id,
			'title'       => $this->title,
			'description' => $this->description,
			'createdAt'   => $this->created_at->format('Y-m-d H:i'),
			'publishedAt' => $this->published_at->format('Y-m-d H:i'),
			'sourceId'    => $this->source->id,
			'categoryId'  => $this->source->category_id,
		];
	}
}
