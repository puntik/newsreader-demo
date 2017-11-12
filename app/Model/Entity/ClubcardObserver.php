<?php declare(strict_types = 1);

namespace App\Model\Entity;

class ClubcardObserver
{

	public function saved(Clubcard $clubcard)
	{
		\Illuminate\Support\Facades\Log::info(sprintf('ClubcardObserver::saved #%d', $clubcard->id));
	}
}
