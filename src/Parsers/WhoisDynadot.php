<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisDynadot extends Generic
{
    protected array $matchRateLimit = [
        'You have been banned for abuse.',
    ];
}
