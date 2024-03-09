<?php

namespace Deaduseful\WhoisParser\src\Records;

use Carbon\Carbon;

class Registration extends Record
{
    public ?Carbon $created;
    public ?Carbon $updated;
    public ?Carbon $expiry;
    public ?string $registrar;
    public ?array $statuses = [];
}
