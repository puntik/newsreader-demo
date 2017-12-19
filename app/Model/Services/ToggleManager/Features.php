<?php declare(strict_types = 1);

namespace App\Model\Services\ToggleManager;

use Eloquent\Enumeration\AbstractEnumeration;

class Features extends AbstractEnumeration
{

	public const GITHUB_LOGIN = 'github_login';

	public const FACEBOOK_LOGIN = 'facebook_login';
}
