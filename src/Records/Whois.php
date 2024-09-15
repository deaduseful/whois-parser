<?php

namespace Deaduseful\WhoisParser\src\Records;

use Carbon\Carbon;
use stdClass;

class Whois extends Record
{
    public ?string $date;
    public ?string $record;
    public ?string $domain;
    public ?Carbon $created_date;
    public ?Carbon $updated_date;
    public ?Carbon $expired_date;
    public array $statuses = [];
    public ?array $name_servers;
    public ?Registrar $registrar;
    public ?stdClass $contacts;
    public ?stdClass $other_properties;
}
