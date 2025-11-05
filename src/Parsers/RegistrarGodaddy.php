<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistrarGodaddy extends Generic
{
    protected array $matchNotFound = [
        'No Data Found',
    ];

    protected array $matchRateLimit = [
        'Number of allowed queries exceeded',
    ];

    protected array $matchInvalid = [
        'TLD is not supported.',
    ];

    protected array $matchReserved = [
        'Reserved Domain Name',
    ];
}
