<?php

namespace Deaduseful\WhoisParser\Parsers;

/**
 * Central configuration for common WHOIS response patterns
 * 
 * This class serves as the single source of truth for pattern matching across
 * all WHOIS parsers. Patterns are organized by category for easy maintenance.
 */
class WhoisPatterns
{
    /**
     * Common patterns indicating a domain is not registered
     */
    public const NOT_FOUND = [
        'No Object Found',
        'No match for',
        'No match found',
        'No matching record',
        'Not find MatchingRecord',
        'No data was found',
        'No Found',
        'DOMAIN NOT FOUND',
        'NOT FOUND',
        'Not found',
        'not found',
        'Not Found',
        'No entries found for the selected source(s)',
        'No entries found',
        'no entries found',
        'Object not found',
        'Object does not exist',
        'object does not exist',
        'The requested domain was not found',
        'Domain not found',
        'Domain not found in database',
        'Domain name not found',
        'Domain not found in database',
        'Domain name not registered',
        'Domain not registered',
        'not registered',
        'Domain not found in registry',
        'not been registered',
        'not known',
        'available for registration',
        'is free',
        'returned 0 objects',
        'No Data Found',
        'No information available',
        'No such domain',
        'Domain unknown',
        'Nothing found',
        'nothing found',
        'AVAILABLE',
        ': free',
    ];

    /**
     * Common patterns indicating a domain is reserved
     */
    public const RESERVED = [
        'Reserved by',
    ];

    /**
     * Common patterns indicating rate limiting
     * 
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/limit-reached-messages.json
     */
    public const RATE_LIMIT = [
        'Query rate exceeded, please try again later.',
    ];
}

