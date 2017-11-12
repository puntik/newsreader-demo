<?php declare(strict_types = 1);

namespace App\Model\Entity;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{

	protected $table = 'sessions';

	public function accessToken()
	{
		$this->hasOne(AccessToken::class, 'access_token_id');
	}
}
