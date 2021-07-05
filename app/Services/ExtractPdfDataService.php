<?php

namespace App\Services;

use App\Library\Pdf\PdfToExcel;

class ExtractPdfDataService
{

    /**
     * ExtractPdfDataService constructor
     * 
     * @param PdfToExcel $pdfToExcel
     */
    public function __construct(protected PdfToExcel $pdfToExcel)
    {
    }

    /**
     * Do the extraction
     * 
     * @param $pdfFile
     * @return boolean
     */
    public function extractPdfData($pdfFile)
    {
        return $this->pdfToExcel->extractPdfData($pdfFile);
    }
}
