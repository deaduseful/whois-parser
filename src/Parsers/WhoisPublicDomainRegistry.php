<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisPublicDomainRegistry extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registryExpiryDate' => 'expiry',
    ];
}
