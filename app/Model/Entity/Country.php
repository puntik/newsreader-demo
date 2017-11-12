<?php declare(strict_types = 1);

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

	protected $table = 'cp_country';

	public $timestamps = false;

	public function clubcards()
	{
		return $this->hasMany(Clubcard::class);
	}
}
