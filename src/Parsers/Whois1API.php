<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Whois1API extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'registrationExpirationDate' => 'expiry',
    ];
}
