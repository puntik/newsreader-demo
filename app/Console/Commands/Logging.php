<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Logging extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tesco:logger-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test logger capabilities';

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

    }
}
