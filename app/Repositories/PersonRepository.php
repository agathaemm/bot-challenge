<?php

namespace App\Repositories;

use App\Models\Person;

class PersonRepository
{

    /**
     * PersonRepository constructor
     * 
     * @param Person $person
     */
    public function __construct(protected Person $person)
    {
    }

    /**
     * Create people in mass
     * 
     * @param $people
     * @return boolean
     */
    public function massCreate($people)
    {
        return Person::insert($people);
    }
}
