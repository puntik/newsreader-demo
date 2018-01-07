<?php declare(strict_types = 1);

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

	protected $table = 'tags';

	protected $fillable = [
		'title',
		'query',
	];

	public function feeds()
	{
		return $this->belongsToMany(Feed::class);
	}
}
