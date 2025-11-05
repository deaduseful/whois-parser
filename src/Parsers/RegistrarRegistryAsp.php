<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistrarRegistryAsp extends Generic
{
    use Traits\NotFoundByLookupStarts;

    protected array $matchNotFound = [
        'Domain Not Found',
    ];

    protected array $mapFields = [
        'creationDate' => 'created',
        'modifiedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
