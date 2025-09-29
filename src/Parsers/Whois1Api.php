<?php

namespace Deaduseful\WhoisParser\Parsers;

class Whois1Api extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'registrationExpirationDate' => 'expiry',
    ];
}
