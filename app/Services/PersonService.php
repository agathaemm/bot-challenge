<?php

namespace App\Services;

use App\Repositories\PersonRepository;
use App\Library\Bot\Bot;

class PersonService
{
    /**
     * PersonService constructor
     * 
     * @param PersonRepository $personRepository
     * @param Bot $bot
     */
    public function __construct(protected PersonRepository $personRepository, protected Bot $bot)
    {
    }

    /**
     * Save table data in database
     * 
     * @return boolean
     */
    public function saveTableInformation()
    {
        // Access page
        $this->bot->linkPage = "https://testpages.herokuapp.com/styled/tag/table.html";

        // Search for people
        $people = $this->bot->extractTableInfo();

        // Saves people
        return $this->personRepository->massCreate($people);
    }
}
