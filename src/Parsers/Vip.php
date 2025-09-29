<?php

namespace Deaduseful\WhoisParser\Parsers;

class Vip extends Generic
{
    protected array $matchRateLimit = [
        'Number of allowed queries exceeded',
    ];
}
