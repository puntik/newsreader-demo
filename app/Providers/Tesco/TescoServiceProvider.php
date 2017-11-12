<?php declare(strict_types = 1);

namespace App\Providers\Tesco;

use App\Model\Tesco;
use Illuminate\Support\ServiceProvider;

class TescoServiceProvider extends ServiceProvider
{

	public function register(): void
	{
		$this->app->bind('tesco', function (): Tesco {
			return new Tesco();
		});
	}
}
