<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Es extends Generic
{
    protected array $matchRateLimit = [
        'exceeded the established limit',
    ];
}
