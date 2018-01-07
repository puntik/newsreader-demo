<?php declare(strict_types = 1);

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{

	protected $table = 'tags';

	protected $fillable = [
		'title',
		'query',
	];

	public function feeds(): BelongsToMany
	{
		return $this->belongsToMany(Feed::class);
	}
}
