<?php

namespace Deaduseful\WhoisParser\src\Records;

class Whois extends Record
{

    public ?string $date;
    public ?string $record;
    public ?string $domain;
    public ?string $created_date;
    public ?string $updated_date;
    public ?string $expired_date;
    public array $statuses = [];
    public mixed $name_servers;
}
