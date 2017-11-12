<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SessionRelations extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tesco:sessions:read';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Try to find and read some session info.';

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
		$token = 'cf16be07-bbe2-4a53-a02c-b9fdfeba4421';

		$session = DB::table('sessions')
					 ->select('cookies')
					 ->join(
						 'access_tokens',
						 'access_tokens.id',
						 '=',
						 'sessions.access_token_id'
					 )
					 ->where(
						 'access_tokens.token',
						 '=',
						 $token
					 )
					 ->first();

		$cookies = $session->cookies;

		return unserialize(base64_decode($cookies));

		dd($data);
	}
}
