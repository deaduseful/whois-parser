<?php

namespace DeadusefulWhoisParser\Parsers;

class Vip extends Generic
{
    protected array $matchRateLimit = [
        'Number of allowed queries exceeded',
    ];
}
