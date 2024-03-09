<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Ro extends Generic
{
    protected array $mapFields = [
        'registeredOn' => 'created',
        'expiresOn' => 'expiry',
    ];
}
