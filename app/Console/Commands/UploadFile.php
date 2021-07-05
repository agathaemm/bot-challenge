<?php

namespace App\Console\Commands;

use App\Services\FillFormService;
use Illuminate\Console\Command;

class UploadFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload-file';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Meke file upload';

    /**
     * Button form
     * 
     * @var string
     */
    public $formBtn = "upload";

    /**
     * Page link to access
     * 
     * @var string
     */
    public $linkPage = "https://testpages.herokuapp.com/styled/file-upload-test.html";

    /**
     * Create a new command instance.
     *
     * @param FillFormService $fillFormService
     * @return void
     */
    public function __construct(protected FillFormService $fillFormService)
    {
        parent::__construct();
    }

    /**
     * Returns form values
     * 
     * @return array
     */
    public function getFormValues()
    {
        return [
            [
                'field_name' => "filename",
                'field_type' => "input",
                'field_value' => "/home/seluser/Downloads/Teste TKS.txt"
            ], [
                'field_name' => "filetype",
                'field_type' => "radio",
                'field_value' => "text"
            ],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return boolean
     */
    public function handle()
    {
        try {
            $this->fillFormService->fillForm(
                $this->linkPage,
                $this->getFormValues(),
                null,
                $this->formBtn
            );
            echo "Arquivo enviado com sucesso!";
            return true;
        } catch (\Throwable $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
