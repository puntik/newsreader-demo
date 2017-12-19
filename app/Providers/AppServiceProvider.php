<?php

namespace App\Providers;

use App\Model\Entity\Feed;
use App\Model\Services\Category\CategoryRepository;
use App\Model\Services\Category\EloquentCategoryRepository;
use App\Model\Services\Downloader;
use App\Model\Services\Elastic;
use App\Model\Services\ToggleManager\FeaturesManager;
use App\Model\Services\ToggleManager\ToggleManagerBuilder;
use App\Observers\FeedObserver;
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
		Feed::observe(FeedObserver::class);
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

		$this->app->bind(ToggleManagerBuilder::class, function (): FeaturesManager {
			return ToggleManagerBuilder::getInstance();
		});

		$this->app->singleton(Downloader::class, function ($app) {
			return new Downloader();
		});

		$this->app->singleton(Elastic::class, function ($app): Elastic {
			return new Elastic(
				$app->make(Client::class)
			);
		});

		$this->app->singleton(CategoryRepository::class, function ($app): CategoryRepository {
			return new EloquentCategoryRepository(
				$app->make(Client::class)
			);
		});
	}
}
