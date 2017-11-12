<?php

namespace App\Console\Commands;

use App\Model\Entity\Clubcard;
use App\Model\Entity\Country;
use Illuminate\Console\Command;

class ClubcardCreator extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tesco:clubcard:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a random clubcard and save it.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$country = Country::find(4);
		$number  = str_random(16);

		$clubcard         = new Clubcard();
		$clubcard->number = $number;
		$clubcard->country()->associate($country);

		$clubcard->save();
	}
}
