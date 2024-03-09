<?php

namespace Deaduseful\WhoisParser\src\Records;

use Carbon\Carbon;

class Registration extends Record
{
    public ?Carbon $created;
    public ?Carbon $expiry;
    public ?Carbon $updated;
    public ?string $registrar;
    public ?array $statuses = [];
}
