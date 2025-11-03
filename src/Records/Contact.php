<?php

namespace Deaduseful\WhoisParser\Records;

use stdClass;

class Contact extends Record
{
    public ?string $name;
    public ?string $org;
    public null|string|array $street;
    public ?string $city;
    public ?string $state;
    public ?string $postal;
    public ?string $country;
    public ?string $phone;
    public ?string $fax;
    public ?string $email;

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->name = $this->name;
        $properties->org = $this->org;
        $properties->street = $this->street;
        $properties->city = $this->city;
        $properties->state = $this->state;
        $properties->postal = $this->postal;
        $properties->country = $this->country;
        $properties->phone = $this->phone;
        $properties->fax = $this->fax;
        $properties->email = $this->email;
        return $properties;
    }
}
