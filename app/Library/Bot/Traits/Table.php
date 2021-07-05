<?php

namespace App\Library\Bot\Traits;

use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Str;

trait Table
{

    /**
     * Get the total rows in the table
     * 
     * @return int
     */
    public function getTableTotalRows()
    {
        return count($this->driver->findElements(WebDriverBy::xpath("//*[@id='mytable']/tbody/tr")));
    }

    /**
     * Get the total columns in the table
     *
     * @return int
     */
    public function getTableTotalCols()
    {
        return count($this->driver->findElements(WebDriverBy::xpath("//*[@id='mytable']/tbody/tr[2]/td")));
    }

    /**
     * Search table key
     * 
     * @param $col
     * @return string
     */
    public function getKeyInformation($col)
    {
        $keyPath  = "//*[@id='mytable']/tbody/tr[1]/th[$col]";
        $key = $this->driver->findElement(WebDriverBy::xpath($keyPath));
        return Str::lower($key->getText());
    }

    /**
     * Search table cell contents
     * 
     * @param $row
     * @param $col
     * @return string
     */
    public function getContentInformation($row, $col)
    {
        $cellPath = "//*[@id='mytable']/tbody/tr[$row]/td[$col]";
        $cell = $this->driver->findElement(WebDriverBy::xpath($cellPath));
        return $cell->getText();
    }

    /**
     * Set date and time
     * 
     * @param $information
     * @return array
     */
    public function setDateTime($information)
    {
        $information['created_at'] = date('Y-m-d H:i:s');
        $information['updated_at'] = date('Y-m-d H:i:s');
        return $information;
    }

    /**
     * Extract the data
     * 
     * @param $totalRows
     * @param $totalCols
     * @return array
     */
    public function doExtraction($totalRows, $totalCols)
    {
        // Initializes the table informations array
        $informations = array();

        // Scroll through the rows of the table
        for ($row = 2; $row <= $totalRows; $row++) {

            // Initializes an table information
            $information = array();

            // Scroll through the cols of the table
            for ($col = 1; $col <= $totalCols; $col++) {
                // Search table key
                $key = $this->getKeyInformation($col);

                // Search current content
                $content = $this->getContentInformation($row, $col);

                // Add the content of information
                $information[$key] = $content;
            }

            // Set timestamp
            $information = $this->setDateTime($information);

            // Add the information to the informations array
            $informations[] = $information;
        }

        // Returns the information extracted from the table 
        return $informations;
    }

    /**
     * Extracts information from the table
     * 
     * @return array
     */
    public function extractTableInformation()
    {
        // Search the total rows in the table
        $totalRows = $this->getTableTotalRows();

        // Search the total cols in the table
        $totalCols = $this->getTableTotalCols();

        // Do the extraction and returns it
        return $this->doExtraction($totalRows, $totalCols);
    }
}
