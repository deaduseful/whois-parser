<?php

namespace Deaduseful\WhoisParser\Records;

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
}
