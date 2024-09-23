<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisEname extends Generic
{
    protected array $matchRateLimit = [
        'Your request is too frequent',
    ];
}
