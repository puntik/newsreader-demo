<?php

namespace App\Listeners\Clubcard;

use App\Events\Clubcard\SavedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClubcardSlackNotificationSavedListener implements ShouldQueue
{

	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  object $event
	 *
	 * @return void
	 */
	public function handle(SavedEvent $event)
	{
		\Illuminate\Support\Facades\Log::info('Notify by slack');
	}
}
