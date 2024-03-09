<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Fr extends Generic
{
    protected array $mapFields = [
        'registered' => 'created',
        'lastUpdate' => 'updated',
        'expiryDate' => 'expiry',
    ];
}
