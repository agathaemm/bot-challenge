<?php

namespace App\Console\Commands;

use App\Services\DownloadFileService;
use Illuminate\Console\Command;
use Throwable;

class DownloadFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'download-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download a file';

    /**
     * Create a new command instance.
     *
     * @param DownloadFileService $downloadFileService
     * @return void
     */
    public function __construct(protected DownloadFileService $downloadFileService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        try {
            $this->downloadFileService->downloadFile("direct-download-a");
            echo "File was downloaded";
            return true;
        } catch (Throwable $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
