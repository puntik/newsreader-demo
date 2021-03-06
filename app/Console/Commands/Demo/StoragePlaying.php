<?php

namespace App\Console\Commands\Demo;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Output\OutputInterface;

class StoragePlaying extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tesco:storage-demo';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Test some storage facilities';

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
		$feedSourcePath = 'feed_source';
		$file1          = 'demo_1.txt';

		/*
		Storage::put($file1, 'Mamlas');
		Storage::put('mamlas/demo_2.txt', 'Hello world');

		$fileExists = Storage::exists($file1);
		$this->info(sprintf('File Exists: %d', $fileExists));

		$fileUrl = Storage::url($file1);
		$this->info($fileUrl);
		*/

		$guzzleClient = new \GuzzleHttp\Client();

		// Storage::makeDirectory($feedSourcePath);

		$url     = 'https://www.idnes.cz/';
		$rssFile = sprintf('%s/1.xml', Storage::path($feedSourcePath));

		/*
		$response = $guzzleClient->get($url, [
			'save_to' => $rssFile,
			'timeout' => 5,
		]);
		*/

		$this->line('line');
		$this->info('info');
		$this->warn('warn');
		$this->error('error');

		$this->info('info - no verbosity set');                                                  // -vvv -vv -v x
		$this->info('info - verobosity NORMAL', OutputInterface::VERBOSITY_NORMAL);              // -vvv -vv -v x
		$this->info('info - verobosity QUIET', OutputInterface::VERBOSITY_QUIET);                // -vvv -vv -v -q x
		$this->info('info - verobosity VERBOSE', OutputInterface::VERBOSITY_VERBOSE);            // -vvv -vv -v
		$this->info('info - verobosity VERY VERBOSE', OutputInterface::VERBOSITY_VERY_VERBOSE);  // -vvv -vv
		$this->info('info - verobosity DEBUG', OutputInterface::VERBOSITY_DEBUG);                // -vvv
	}
}
