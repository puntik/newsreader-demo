<?php declare(strict_types = 1);

namespace App\Console\Commands\Reader;

use App\Model\Services\Downloader;
use App\Model\Services\Elastic;
use Illuminate\Console\Command;

class DowloaderCommand extends Command
{

	/** @var string */
	protected $signature = 'newsreader:load-feeds';

	/** @var string */
	protected $description = 'Load all feeds and save them to database';

	/** @var Elastic */
	private $elastic;

	/** @var Downloader */
	private $downloader;

	public function __construct(
		Downloader $downloader,
		Elastic $elastic
	) {
		parent::__construct();

		$this->elastic    = $elastic;
		$this->downloader = $downloader;
	}

	public function handle()
	{
		$this->info('Processing sources to download is about to start.');
		$this->downloader->processSources();
	}
}
