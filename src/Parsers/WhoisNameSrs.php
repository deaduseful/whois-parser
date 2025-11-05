<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisNameSrs extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'lastUpdateOfWHOISDatabase' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
    ];
}
