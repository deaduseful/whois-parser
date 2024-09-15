<?php

namespace Deaduseful\WhoisParser\src\Records;

class Registration extends Record
{
    public ?string $created;
    public ?string $updated;
    public ?string $expiry;
    public ?string $registrar;
    public ?array $statuses = [];
}
