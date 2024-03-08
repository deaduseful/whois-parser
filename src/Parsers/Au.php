<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Au extends Generic
{
    protected array $matchRateLimit = [
        'WHOIS LIMIT EXCEEDED',
    ];
}
