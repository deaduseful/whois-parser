<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisGodaddy extends Generic
{
    protected array $matchRateLimit = [
        'whois.godaddy.com' => 'Rate limit exceeded',
    ];
}
