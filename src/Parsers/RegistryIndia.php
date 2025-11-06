<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistryIndia extends Generic
{
    protected array $matchNotFound = [
        'No Data Found',
        'NOT FOUND',
    ];

    protected array $matchReserved = [
        'Domain name matches a restricted word',
    ];
    
    protected array $matchInvalid = [
        'Tld not supported by this registry interface',
    ];

    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registryExpiryDate' => 'expiry',
    ];
}
