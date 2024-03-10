<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Info extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registryExpiryDate' => 'expiry',
    ];
}
