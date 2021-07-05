<?php

namespace App\Console\Commands;

use App\Services\FillFormService;
use Illuminate\Console\Command;
use Throwable;

class FillForm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill in the form';

    /**
     * Form id
     * 
     * @var string
     */
    public $formId = "HTMLFormElements";

    /**
     * Link to access the page
     * 
     * @var string
     */
    public $linkPage = "https://testpages.herokuapp.com/styled/basic-html-form-test.html";

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
     * Returns form value
     * 
     * @return array
     */
    public function getFormValues()
    {
        return [
            [
                'field_name' => "username",
                'field_type' => "input",
                'field_value' => "agatha"
            ], [
                'field_name' => "password",
                'field_type' => "input",
                'field_value' => "senha123"
            ], [
                'field_name' => "comments",
                'field_type' => "input",
                'field_value' => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum eaque fugiat fuga ea culpa. Nulla inventore doloribus cupiditate rem architecto dolorum, aspernatur quas autem reprehenderit enim, nobis alias dicta voluptatum."
            ], [
                'field_name' => "filename",
                'field_type' => "file",
                'field_value' => storage_path('image_exe.png')
            ], [
                'field_name' => "checkboxes[]",
                'field_type' => "checkbox",
                'field_value' => "cb2"
            ], [
                'field_name' => "radioval",
                'field_type' => "radio",
                'field_value' => "rd3"
            ], [
                'field_name' => "multipleselect[]",
                'field_type' => "multiselect",
                'field_value' => array("ms1", "ms2")
            ], [
                'field_name' => "dropdown",
                'field_type' => "dropdown",
                'field_value' => "dd1"
            ]
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
                $this->formId
            );
            echo "Formulário preenchido com sucesso";
            return true;
        } catch (Throwable $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }
}
