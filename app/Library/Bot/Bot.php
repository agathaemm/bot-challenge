<?php

namespace App\Library\Bot;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use App\Library\Bot\Traits\Table;
use App\Library\Bot\Traits\Form;

class Bot
{
    use Table, Form;

    /**
     * Selenium server url
     * 
     * @var string
     */
    protected $serverUrl = "http://selenium:4444/wd/hub";

    /**
     * Driver
     * 
     * @var RemoteWebDriver
     */
    protected $driver;

    /**
     * Link page
     * 
     * @var string
     */
    public string $linkPage;

    /**
     * Bot constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->initializeDriver();
    }

    /**
     * Start driver
     * 
     * @return void
     */
    public function initializeDriver()
    {
        $this->driver = RemoteWebDriver::create($this->serverUrl, DesiredCapabilities::chrome());
    }

    /**
     * Access the page to be manipulated
     * 
     * @return void
     */
    public function accessPage()
    {
        $this->driver->get($this->linkPage);
    }

    /**
     * Finish browser session
     * 
     * @return void
     */
    public function finishBrowserSession()
    {
        $this->driver->quit();
    }

    /**
     * Calls the function that extracts the data from the table 
     * 
     * @return array
     */
    public function extractTableInfo()
    {
        // Access page
        $this->accessPage();

        // Search table info
        $tableInformations = $this->extractTableInformation();

        // Finish session
        $this->finishBrowserSession();

        // Return infos
        return $tableInformations;
    }

    /**
     * Fill data in form
     * 
     * @param $formValues
     * @param $formId
     * @param $submitBtn
     * @return boolean
     */
    public function fillForm($formValues, $formId = null, $submitBtn = null)
    {
        // Access page
        $this->accessPage();

        // Fill data form
        $this->fill($formValues);

        $this->driver->takeScreenShot(storage_path('image1.png'));

        // Submit form depending by id or button
        if ($formId) {
            $this->submitByFormId($formId);
        } else {
            $this->submitByFormBtn($submitBtn);
        }

        // Waits until form is submited
        sleep(5);

        $this->driver->takeScreenShot(storage_path('image2.png'));

        // Finish session
        $this->finishBrowserSession();

        // Return true by default
        return true;
    }

    /**
     * Make the download file
     * 
     * @param $downloadElement
     * @return boolean
     */
    public function downloadFile($downloadElement)
    {
        // Remove previous files
        @unlink(storage_path("files/textfile.txt"));
        @unlink(storage_path("files/Teste TKS.txt"));

        // Access page
        $this->accessPage();

        // Sleeps for 5s
        sleep(5);

        // Do the download
        $this->driver->findElement(WebDriverBy::id($downloadElement))->click();

        // Sleeps for 5s
        sleep(5);

        // Rename file
        rename(storage_path('files/textfile.txt'), storage_path('files/Teste TKS.txt'));

        // Finish session
        $this->finishBrowserSession();

        // Return true by default
        return true;
    }
}
