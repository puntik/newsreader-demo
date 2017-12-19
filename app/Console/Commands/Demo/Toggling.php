<?php

namespace App\Console\Commands\Demo;

use App\Facade\ToggleManager;
use App\Model\Services\ToggleManager\Features;
use Illuminate\Console\Command;

class Toggling extends Command
{

	/** @var string */
	protected $signature = 'tesco:toggling-demo';

	/** @var string */
	protected $description = 'Demo for toggling features';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		var_dump(ToggleManager::isActive(Features::GITHUB_LOGIN));
	}
}
