<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Rs extends Generic
{
    protected array $mapFields = [
        'registrationDate' => 'created',
        'modificationDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
