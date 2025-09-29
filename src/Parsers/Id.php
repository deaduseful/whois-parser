<?php

namespace DeadusefulWhoisParser\Parsers;

class Id extends Generic
{
    protected array $mapFields = [
        'createdOn' => 'created',
        'lastUpdatedOn' => 'updated',
        'expirationDate' => 'expiry',
    ];
    protected array $matchNotFound = [
        'DOMAIN NOT FOUND',
    ];
}
