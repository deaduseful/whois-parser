<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Fi extends Generic
{
    protected array $mapFields = [
        'created' => 'created',
        'modified' => 'updated',
        'expires' => 'expiry',
    ];
}
