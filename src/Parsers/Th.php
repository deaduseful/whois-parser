<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Th extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'expDate' => 'expiry',
    ];
}
