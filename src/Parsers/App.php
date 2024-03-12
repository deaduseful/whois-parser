<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class App extends RegistrarGoogle
{
    protected array $matchRateLimit = [
        'Rate limit exceeded',
    ];
    protected array $matchNotFound = [
        'Domain not found.',
    ];
}
