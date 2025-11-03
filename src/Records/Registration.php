<?php

namespace Deaduseful\WhoisParser\Records;

use stdClass;

class Registration extends Record
{
    public ?string $created;
    public ?string $updated;
    public ?string $expiry;
    public ?string $registrar;
    public ?array $statuses = [];

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->created = $this->created;
        $properties->updated = $this->updated;
        $properties->expiry = $this->expiry;
        $properties->registrar = $this->registrar;
        $properties->statuses = $this->statuses;
        return $properties;
    }
}
