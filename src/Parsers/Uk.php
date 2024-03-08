<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Uk extends Generic
{
    protected array $matchRateLimit = [
        'the WHOIS query quota',
    ];
}
