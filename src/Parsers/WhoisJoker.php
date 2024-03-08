<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisJoker extends Generic
{
    protected array $matchRateLimit = [
        'whois.joker.com' => 'Service is temporarily not available',
    ];
}
