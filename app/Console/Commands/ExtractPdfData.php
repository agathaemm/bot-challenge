<?php

namespace App\Console\Commands;

use App\Services\ExtractPdfDataService;
use Illuminate\Console\Command;
use Throwable;

class ExtractPdfData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract-pdf-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extract data from pdf';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(protected ExtractPdfDataService $extractPdfDataService)
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
            $this->extractPdfDataService->extractPdfData(public_path("files/Leitura.pdf"));
            echo 'Dados armazenados com sucesso';
            return true;
        } catch (Throwable $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
