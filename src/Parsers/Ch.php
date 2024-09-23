<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Ch extends Generic
{
    protected array $matchRateLimit = [
        'Requests of this client are not permitted',
        'temporarily not available',
    ];
}
