<?php

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

	protected $table = 'category';

	protected $dates = [
		'created_at',
		'published_at',
	];

	public function sources()
	{
		return $this->hasMany(Source::class);
	}

	public function feeds()
	{
		return $this->hasManyThrough(Feed::class, Source::class);
	}

	public function routeSlug(): string
	{
		return route('category', ['id' => $this->id, 'name' => str_slug($this->title)]);
	}
}
