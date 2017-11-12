<?php

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{

	protected $table = 'feed';

	protected $dates = [
		'created_at',
		'published_at',
	];

	public function source()
	{
		return $this->belongsTo(Source::class);
	}
}
