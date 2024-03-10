<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Dk extends Generic
{
    protected array $mapFields = [
        'registered' => 'created',
        'expires' => 'expiry',
    ];
}
