<?php

namespace App\Providers;

use App\Model\Entity\Clubcard;
use App\Model\Entity\ClubcardObserver;
use App\Model\Services\Elastic;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Clubcard::observe(ClubcardObserver::class);
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		if ($this->app->environment() !== 'production') {
			$this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
		}

		$this->app->bind(Client::class, function ($app): Client {
			return ClientBuilder::create()
								->setHosts(['localhost'])
								->build();
		});

		$this->app->singleton(Elastic::class, function ($app): Elastic {
			return new Elastic(
				$app->make(Client::class)
			);
		});
	}
}
