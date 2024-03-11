<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Uk extends Generic
{
    protected array $matchRateLimit = [
        'the WHOIS query quota',
    ];
    protected array $matchNotFound = [
        'This domain name has not been registered.',
    ];
}
