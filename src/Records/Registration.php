<?php

namespace Deaduseful\WhoisParser\src\Records;

class Registration extends Record
{
    public ?string $created;
    public ?string $expires;
    public ?string $updated;
    public ?string $registrar;
    public ?array $statuses = [];
}
