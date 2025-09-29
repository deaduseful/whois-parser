<?php

namespace DeadusefulWhoisParser\Parsers;

class WhoisNameSrs extends RegistrarCentralNic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'lastUpdateOfWHOISDatabase' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
    ];
}
