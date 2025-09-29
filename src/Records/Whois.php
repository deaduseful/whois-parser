<?php

namespace DeadusefulWhoisParser\Records;

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
}
