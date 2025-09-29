<?php

namespace DeadusefulWhoisParser\Parsers;

class Cz extends Generic
{
    protected array $matchRateLimit = [
        'Your connection limit exceeded',
    ];
}
