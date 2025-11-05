<?php

namespace Deaduseful\WhoisParser\Parsers;

class NicGoogle extends Generic
{
    protected array $matchNotFound = [
        'Domain not found.',
    ];

    protected array $matchRateLimit = [
        'Rate limit exceeded',
    ];
}
