<?php

namespace Deaduseful\WhoisParser\Records;

use stdClass;

class Whois extends Record
{
    public ?string $date;
    public ?string $record;
    public ?string $domain;
    public ?string $created_date;
    public ?string $updated_date;
    public ?string $expired_date;
    public array $statuses = [];
    public ?array $name_servers;
    public ?Registrar $registrar;
    public ?stdClass $contacts;
    public ?stdClass $other_properties;

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->date = $this->date;
        $properties->record = $this->record;
        $properties->domain = $this->domain;
        $properties->created_date = $this->created_date;
        $properties->updated_date = $this->updated_date;
        $properties->expired_date = $this->expired_date;
        $properties->statuses = $this->statuses;
        $properties->name_servers = $this->name_servers;
        $properties->registrar = $this->registrar;
        $properties->contacts = $this->contacts;
        $properties->other_properties = $this->other_properties;
        return $properties;
    }
}
