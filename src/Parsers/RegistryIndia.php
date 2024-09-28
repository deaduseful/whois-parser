<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class RegistryIndia extends Generic
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
