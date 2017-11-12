<?php declare(strict_types = 1);

namespace App\Listeners\Clubcard;

use App\Events\Clubcard\SavedEvent;
use Illuminate\Support\Facades\Log;

class SavedListener
{

	public function __construct()
	{
	}

	public function handle(SavedEvent $event)
	{
		Log::info('Handling clubcard canceled event');
	}
}
