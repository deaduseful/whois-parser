<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Dk extends Generic
{
    protected array $matchNotFound = [
        'No entries found for the selected source.',
    ];

    protected array $mapFields = [
        'registered' => 'created',
        'expires' => 'expiry',
    ];
}
