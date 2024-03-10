<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Social extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
    ];
}
