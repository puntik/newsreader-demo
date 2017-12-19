<?php declare(strict_types = 1);

namespace App\Model\Services\ToggleManager;

use Qandidate\Toggle\Context;
use Qandidate\Toggle\Operator\EqualTo;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;

class ToggleManagerBuilder implements FeaturesManager
{

	/** @var ToggleManager */
	private $toggleManager;

	/** @var Context */
	private $toggleContext;

	private function __construct()
	{
		$this->toggleManager = $this->buildManager();
		$this->toggleContext = $this->buildContext();
	}

	public static function getInstance(): FeaturesManager
	{
		return new self();
	}

	private function buildContext(): Context
	{
		$toggleContext = new Context();
		$toggleContext->set('allow_github_login', env('GITHUB_LOGIN_ALLOW', false));

		return $toggleContext;
	}

	private function buildManager(): ToggleManager
	{
		// create manager
		$manager = new ToggleManager(new InMemoryCollection());

		// allow github login
		$operator  = new EqualTo(true);
		$condition = new OperatorCondition('allow_github_login', $operator);
		$toggle    = new Toggle(Features::GITHUB_LOGIN, [$condition]);
		$manager->add($toggle);

		return $manager;
	}

	public function isActive(string $feature): bool
	{
		return $this->toggleManager->active(
			$feature,
			$this->toggleContext
		);
	}
}
