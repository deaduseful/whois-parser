<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Sg extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'modifiedDate' => 'updated',
        'expirationDate' => 'expiry',
        'nserver' => 'nameServer',
    ];
}
