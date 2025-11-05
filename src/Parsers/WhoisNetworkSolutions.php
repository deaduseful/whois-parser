<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisNetworkSolutions extends Generic
{
    protected array $matchRateLimit = [
        'IP Address Has Reached Rate Limit',
    ];
}
