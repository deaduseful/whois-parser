<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisEname extends Generic
{
    protected array $matchRateLimit = [
        'Your request is too frequent',
    ];
}
