<?php declare(strict_types = 1);

namespace App\Facade;

class Tesco extends \Illuminate\Support\Facades\Facade
{

	protected static function getFacadeAccessor()
	{
		return 'tesco';
	}

}
