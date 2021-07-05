<?php

namespace App\Services;

use App\Library\Bot\Bot;

class DownloadFileService
{

    /**
     * DownloadFileService constructor
     * 
     * @param Bot $bot
     */
    public function __construct(protected Bot $bot)
    {
    }

    /**
     * Download file
     * 
     * @param $downloadElement
     * @return boolean
     */
    public function downloadFile($downloadElement)
    {
        // Access page
        $this->bot->linkPage = "https://testpages.herokuapp.com/styled/download/download.html";

        // Fill form
        return $this->bot->downloadFile($downloadElement);
    }
}
