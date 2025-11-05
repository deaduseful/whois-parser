<?php

namespace Deaduseful\WhoisParser\Parsers;

class Id extends Generic
{
    protected array $matchNotFound = [
        'DOMAIN NOT FOUND',
    ];

    protected array $mapFields = [
        'createdOn' => 'created',
        'lastUpdatedOn' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
