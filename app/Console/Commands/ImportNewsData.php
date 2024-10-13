<?php

namespace App\Console\Commands;

use App\Jobs\ImportNewsDataJob;
use Illuminate\Console\Command;

class ImportNewsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:news-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import news data from different channels';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ImportNewsDataJob::dispatch()->onQueue('low');

        $this->info('Import news data from different channels');
    }
}
