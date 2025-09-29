<?php

namespace Deaduseful\WhoisParser\Parsers;

class Cz extends Generic
{
    protected array $matchRateLimit = [
        'Your connection limit exceeded',
    ];
}
