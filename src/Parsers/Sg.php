<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Sg extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'modifiedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
