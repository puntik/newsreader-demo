<?php

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{

	protected $table = 'source';

	protected $dates = [
		'created_at',
		'published_at',
		'deleted_at',
	];

	public function feeds(): HasMany
	{
		return $this->hasMany(Feed::class);
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function enable(): self
	{
		$this->active = true;

		return $this;
	}

	public function disable(): self
	{
		$this->active = false;

		return $this;
	}
}
