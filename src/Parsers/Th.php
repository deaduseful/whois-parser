<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Th extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'expDate' => 'expiry',
    ];
}
