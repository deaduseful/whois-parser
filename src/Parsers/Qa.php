<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Qa extends Generic
{
    protected array $matchRateLimit = [
        'BLACKLISTED: You have exceeded the query limit for your network or IP address and have been blacklisted.',
    ];
    protected array $matchNotFound = [
        'No Data Found',
    ];

    protected array $matchReserved = [
        'Reserved by QDR',
    ];
}
