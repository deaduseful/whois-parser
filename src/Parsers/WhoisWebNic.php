<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisWebNic extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
