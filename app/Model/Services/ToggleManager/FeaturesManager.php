<?php declare(strict_types = 1);

namespace App\Model\Services\ToggleManager;

interface FeaturesManager
{

	public function isActive(string $feature): bool;
}
