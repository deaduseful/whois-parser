<?php

namespace Deaduseful\WhoisParser\src\Parsers;

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
