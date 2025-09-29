<?php

namespace Deaduseful\WhoisParser\Parsers;

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
