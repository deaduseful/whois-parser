<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Md extends Generic
{
    protected array $mapFields = [
        'registeredOn' => 'created',
        'expiresOn' => 'expiry',
    ];
}
