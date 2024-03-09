<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Tr extends Generic
{
    protected array $mapFields = [
        'createdOn' => 'created',
        'lastUpdateTime' => 'updated',
        'expiresOn' => 'expires',
    ];
}
