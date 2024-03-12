<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Tr extends Generic
{
    protected array $mapFields = [
        'createdOn' => 'created',
        'lastUpdateTime' => 'updated',
        'expiresOn' => 'expiry',
    ];
    protected array $matchNotFound = [
        'No match found for ',
    ];

    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
