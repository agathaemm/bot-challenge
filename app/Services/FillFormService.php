<?php

namespace App\Services;

use App\Library\Bot\Bot;

class FillFormService
{
    /**
     * FillFormService constructor
     * 
     * @param Bot $bot
     */
    public function __construct(protected Bot $bot)
    {
    }

    /**
     * Fill form
     * 
     * @param $link
     * @param $formValues
     * @param $formId
     * @param $formBtn
     * @return boolean
     */
    public function fillForm($link, $formValues, $formId = null, $formBtn = null)
    {
        // Access page
        $this->bot->linkPage = $link;

        // Fill form
        return $this->bot->fillForm($formValues, $formId, $formBtn);
    }
}
