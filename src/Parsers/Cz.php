<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Cz extends Generic
{
    protected array $matchRateLimit = [
        'Your connection limit exceeded',
    ];
}
