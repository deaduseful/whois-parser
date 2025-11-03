<?php

namespace Deaduseful\WhoisParser\Records;

use stdClass;

/**
 * Transferability record
 * 
 * Represents whether a domain can be transferred based on its WHOIS data
 */
class Transferability extends Record
{
    public ?bool $transferable = null;
    public ?array $reasons = null;

    public function isTransferable(): ?bool
    {
        return $this->transferable;
    }

    public function getReasons(): ?array
    {
        return $this->reasons;
    }

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->transferable = $this->transferable;
        $properties->reasons = $this->reasons;
        return $properties;
    }
}

