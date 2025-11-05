<?php

namespace Deaduseful\WhoisParser\Parsers;

class Md extends Generic
{
    protected array $matchNotFound = [
        'No match for',
    ];

    protected array $mapFields = [
        'registeredOn' => 'created',
        'expiresOn' => 'expiry',
    ];
}
