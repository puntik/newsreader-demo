<?php declare(strict_types = 1);

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{

	protected $table = 'access_tokens';

	public function sessions()
	{
		return $this->belongsTo(Session::class);
	}
}
