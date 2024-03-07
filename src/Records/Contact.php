<?php

namespace Deaduseful\WhoisParser\src\Records;

class Contact extends Record
{

    public ?string $type;
    public ?string $name;
    public ?string $org;
    public ?array $street = [];
    public ?string $city;
    public ?string $state;
    public ?string $postal;
    public ?string $country;
    public ?string $phone;
    public ?string $fax;
    public ?string $email;
}
