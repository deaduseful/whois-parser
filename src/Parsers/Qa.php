<?php

namespace Deaduseful\WhoisParser\Parsers;

class Qa extends Generic
{
    protected array $matchNotFound = [
        'No Data Found',
    ];

    protected array $matchRateLimit = [
        'BLACKLISTED: You have exceeded the query limit for your network or IP address and have been blacklisted.',
    ];

    protected array $matchReserved = [
        'Reserved by QDR',
    ];
}
