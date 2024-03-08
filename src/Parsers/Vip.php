<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Vip extends Generic
{
    protected array $matchRateLimit = [
        'Number of allowed queries exceeded',
    ];
}
