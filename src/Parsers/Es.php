<?php

namespace DeadusefulWhoisParser\Parsers;

class Es extends Generic
{
    protected array $matchRateLimit = [
        'exceeded the established limit',
    ];
}
