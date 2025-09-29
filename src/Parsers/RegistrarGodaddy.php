<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistrarGodaddy extends Generic
{
    protected array $matchRateLimit = [
        'Rate limit exceeded',
    ];
    protected array $matchNotFound = [
        'No Data Found',
    ];
    protected array $matchReserved = [
        'Reserved Domain Name',
    ];

    protected array $matchInvalid = [
        'TLD is not supported.',
    ];
}
