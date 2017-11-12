<?php declare(strict_types = 1);

namespace App\Console\Commands;

use App\Facade\Tesco;
use Illuminate\Console\Command;

class SayHello extends Command
{

	/** @var string */
	protected $signature = '
    	tesco:read-data
    	{user : User to load}
    	{--f|force : Force when user is out of office}';

	/** @var string */
	protected $description = 'Read all data and process them.';

	public function handle(): void
	{
		$user   = $this->argument('user');
		$forced = $this->option('force');

		$message = sprintf(
			'Hello %s, this is %s message.',
			$user,
			$forced ? 'forced' : 'not forced'
		);

		$this->info('Hello world.');
		$this->line($message);
	}
}
