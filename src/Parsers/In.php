<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class In extends Generic
{
    protected array $matchNotFound = [
        'No Data Found',
    ];

    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registryExpiryDate' => 'expiry',
    ];
}
