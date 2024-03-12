<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class GenericGodaddy extends Generic
{
    protected array $matchRateLimit = [
        'Rate limit exceeded',
    ];
    protected array $matchNotFound = [
        'No Data Found',
    ];
    protected array $matchInvalid = [
        'Reserved Domain Name',
    ];
}
