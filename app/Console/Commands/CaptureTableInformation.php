<?php

namespace App\Console\Commands;

use App\Services\PersonService;
use Illuminate\Console\Command;
use Throwable;

class CaptureTableInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capture-table-information';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture all the information displayed in the table and store it in the people table';

    /**
     * Create a new command instance.
     *
     * @param PersonService $personService
     * @return void
     */
    public function __construct(protected PersonService $personService)
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
            $this->personService->saveTableInformation();
            echo "Dados salvos do sucesso";
            return true;
        } catch (Throwable $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
