<?php

namespace DeadusefulWhoisParser\Parsers;

class Gdn extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'modifiedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
    protected array $matchNotFound = [
        'Domain Not Found',
    ];
    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
