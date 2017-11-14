<?php

namespace App\Console\Commands\Demo;

use Illuminate\Console\Command;

class PdfFileHandler extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tesco:pdf-check {filename}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Try to find out if file is pdf.';

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
		$filename = $this->argument('filename');
		$this->line(sprintf('Opening file: %s', $filename));

		$mimeType = \Illuminate\Support\Facades\File::mimeType($filename);

		$this->line(sprintf('Mime-Type: %s', $mimeType));
	}
}
