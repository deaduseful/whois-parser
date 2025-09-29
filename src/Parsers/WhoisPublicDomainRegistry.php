<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisPublicDomainRegistry extends RegistrarIdentityDigital
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registryExpiryDate' => 'expiry',
    ];
}
