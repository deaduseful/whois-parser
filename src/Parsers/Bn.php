<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Bn extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'modifiedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
