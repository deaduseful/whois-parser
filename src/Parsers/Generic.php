<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Generic extends KeyValue implements Strategy
{
    /**
     * @var string[] Rate Limit Matching
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/limit-reached-messages.json
     */
    const MATCH_RATE_LIMIT = [
        'Query rate exceeded, please try again later.',
    ];

    /**
     * @var string[] Not Found Matching
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/whois.json
     */
    protected array $matchNotFound = [
        'Domain not found',
        'No match for',
    ];
    protected array $matchRateLimit = [
        'Query rate exceeded, please try again later.',
    ];
}
