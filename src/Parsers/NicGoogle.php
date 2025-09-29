<?php

namespace Deaduseful\WhoisParser\Parsers;

class NicGoogle extends RegistrarGoogle
{
    protected array $matchRateLimit = [
        'Rate limit exceeded',
    ];
    protected array $matchNotFound = [
        'Domain not found.',
    ];
}
