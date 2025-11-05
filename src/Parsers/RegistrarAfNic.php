<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistrarAfNic extends Generic
{
    protected array $matchNotFound = [
        '%% NOT FOUND',
    ];

    protected array $mapFields = [
        'registered' => 'created',
        'lastUpdate' => 'updated',
        'expiryDate' => 'expiry',
    ];
}
