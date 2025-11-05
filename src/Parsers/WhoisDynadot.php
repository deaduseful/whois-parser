<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisDynadot extends Generic
{
    protected array $matchRateLimit = [
        'You have been banned for abuse.',
    ];
}
