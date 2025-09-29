<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisNetworkSolutions extends Generic
{
    protected array $matchRateLimit = [
        'whois.networksolutions.com' => 'IP Address Has Reached Rate Limit',
    ];
}
