<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'App\Events\Event'                     => [
			'App\Listeners\EventListener',
		],
		\App\Events\Clubcard\SavedEvent::class => [
			\App\Listeners\Clubcard\SavedListener::class,
			\App\Listeners\Clubcard\ClubcardSlackNotificationSavedListener::class,
		],
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();
		//
	}
}
