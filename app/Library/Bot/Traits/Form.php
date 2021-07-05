<?php

namespace App\Library\Bot\Traits;

use Facebook\WebDriver\Remote\LocalFileDetector;
use Facebook\WebDriver\WebDriverCheckboxes;
use Facebook\WebDriver\WebDriverRadios;
use Facebook\WebDriver\WebDriverSelect;
use Facebook\WebDriver\WebDriverBy;

trait Form
{
    /**
     * Fill the regulars fields 
     * 
     * @param $name
     * @param $value
     * @return void
     */
    public function fillInput($name, $value)
    {
        $this->driver->findElement(WebDriverBy::name($name))->clear()->sendKeys($value);
    }

    /**
     * Fill file fields
     * 
     * @param $name
     * @param $value
     * @return void
     */
    public function fillFile($name, $value)
    {
        $fileInput = $this->driver->findElement(WebDriverBy::name($name));
        $fileInput->setFileDetector(new LocalFileDetector());
        $fileInput->sendKeys($value);
    }

    /**
     * Fill checkboxes fields
     * 
     * @param $name
     * @param $value
     * @return void
     */
    public function fillCheckbox($name, $value)
    {
        $checkboxesElement = $this->driver->findElement(WebDriverBy::name($name));
        $checkboxes = new WebDriverCheckboxes($checkboxesElement);
        $checkboxes->deselectAll();
        $checkboxes->selectByValue($value);
    }

    /**
     * Fill radio fields
     * 
     * @param $name
     * @param $value
     * @return void
     */
    public function fillRadio($name, $value)
    {
        $radiosElement = $this->driver->findElement(WebDriverBy::name($name));
        $radios = new WebDriverRadios($radiosElement);
        $radios->selectByValue($value);
    }

    /**
     * Fill multiselect fields
     * 
     * @param $name
     * @param $values
     * @return void
     */
    public function fillMultiselect($name, $values)
    {
        $multiselectElement = $this->driver->findElement(WebDriverBy::name($name));
        $multiselect = new WebDriverSelect($multiselectElement);
        $multiselect->deselectAll();
        collect($values)->each(fn ($value) => $multiselect->selectByValue($value));
    }

    /**
     * Fill dropdown fields
     * 
     * @param $name
     * @param $value
     * @return void
     */
    public function fillDropdown($name, $value)
    {
        $selectElement = $this->driver->findElement(WebDriverBy::name($name));
        $select = new WebDriverSelect($selectElement);
        $select->selectByValue($value);
    }

    /**
     * Fill form with some data
     * 
     * @param $formValues
     * @return boolean
     */
    public function fill($formValues)
    {
        // Scrolls through form values
        foreach ($formValues as $formValue) {

            // Get function name
            $funcName = "fill" . ucfirst($formValue["field_type"]);

            // Fill the field
            $this->$funcName($formValue["field_name"], $formValue["field_value"]);
        }

        // Return true by default
        return true;
    }

    /**
     * Submit form by form id
     * 
     * @param $formId
     * @return void
     */
    public function submitByFormId($formId)
    {
        $this->driver->findElement(WebDriverBy::id($formId))->submit();
    }

    /**
     * Submit form by clicking the submit button
     * 
     * @param $formBtn
     * @return void
     */
    public function submitByFormBtn($formBtn)
    {
        $this->driver->findElement(WebDriverBy::name($formBtn))->click();
    }
}
