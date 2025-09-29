<?php

namespace DeadusefulWhoisParser\Parsers;

class WhoisDynadot extends Generic
{
    protected array $matchRateLimit = [
        'You have been banned for abuse.',
    ];
}
