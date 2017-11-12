<?php declare(strict_types = 1);

namespace App\Events\Clubcard;

use App\Model\Entity\Clubcard;

class SavedEvent
{

	/** @var Clubcard */
	private $clubcard;

	public function __construct(Clubcard $clubcard)
	{
		$this->clubcard = $clubcard;
	}
}
