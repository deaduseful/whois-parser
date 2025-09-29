<?php

namespace DeadusefulWhoisParser\Parsers;

class Whois1Api extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'registrationExpirationDate' => 'expiry',
    ];
}
