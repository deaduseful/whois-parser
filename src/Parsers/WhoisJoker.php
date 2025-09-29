<?php

namespace DeadusefulWhoisParser\Parsers;

class WhoisJoker extends Generic
{
    protected array $matchRateLimit = [
        'ERROR: Service is temporarily not available; please try again later',
        'ERROR: Too many requests; rate limited',
    ];
}
