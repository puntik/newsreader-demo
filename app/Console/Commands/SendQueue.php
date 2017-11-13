<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendQueue extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tesco:send-job';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Send simple message to a job queue';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->line('Sending a message to queue broker');

		$later = \Carbon\Carbon::now()->addSeconds(10);

		\Illuminate\Support\Facades\Queue::later($later, \App\Jobs\SendEmail::class, ['to' => 'vk@rem.cz',]);
	}
}
