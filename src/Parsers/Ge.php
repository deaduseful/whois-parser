<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Ge extends Generic
{
    protected array $matchNotFound = [
        'No match for',
    ];
    protected array $mapFields = [
        'creationDate' => 'created',
        'registryExpiryDate' => 'expiry',
    ];
}
