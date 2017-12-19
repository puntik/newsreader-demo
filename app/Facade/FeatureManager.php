<?php declare(strict_types = 1);

namespace App\Facade;

use App\Model\Services\ToggleManager\ToggleManagerBuilder;
use Illuminate\Support\Facades\Facade;

class FeatureManager extends Facade
{

	protected static function getFacadeAccessor()
	{
		return ToggleManagerBuilder::class;
	}
}
