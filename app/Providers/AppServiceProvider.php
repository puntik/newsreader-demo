<?php

namespace App\Providers;

use App\Model\Entity\Clubcard;
use App\Model\Entity\ClubcardObserver;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
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
	}
}
