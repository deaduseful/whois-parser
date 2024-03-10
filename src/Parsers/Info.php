<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Info extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'registryExpiryDate' => 'expiry',
    ];
}
