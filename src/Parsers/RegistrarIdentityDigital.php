<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class RegistrarIdentityDigital extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
    ];
}
