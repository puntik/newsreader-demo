<?php

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Feed extends Model
{

	use Searchable;

	protected $table = 'feed';

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
		return 'a1';
	}

}
