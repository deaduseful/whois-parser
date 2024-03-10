<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Ly extends Generic
{
    protected array $mapFields = [
        'created' => 'created',
        'updated' => 'updated',
        'expired' => 'expiry',
    ];
}
