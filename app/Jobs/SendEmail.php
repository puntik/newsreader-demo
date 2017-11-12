<?php

namespace App\Jobs;

use App\Facade\Tesco;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendEmail implements ShouldQueue
{

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public function fire(Job $job, $data): void
	{
		Log::info('Firing sending email', $data);
		Tesco::login();

		$job->delete();
	}
}
