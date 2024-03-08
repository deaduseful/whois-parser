<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Org extends Generic
{
    protected array $matchRateLimit = [
        'WHOIS LIMIT EXCEEDED',
    ];
}
