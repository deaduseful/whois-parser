<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisNameSrs extends GenericCentralNic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'lastUpdateOfWHOISDatabase' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
    ];
}
