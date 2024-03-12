<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Org extends RegistrarIdentityDigital
{
    protected array $matchNotFound = [
        'Domain not found.',
    ];
    protected array $matchRateLimit = [
        'WHOIS LIMIT EXCEEDED',
    ];
}
