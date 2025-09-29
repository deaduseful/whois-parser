<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class Org extends RegistrarIdentityDigital
{
    protected array $matchNotFound = [
        'Domain not found.',
    ];
    protected array $matchRateLimit = [
        'WHOIS LIMIT EXCEEDED',
    ];

    // Fixes: Could not parse '2004-01-28T00:03:010Z': Failed to parse time string (2004-01-28T00:03:010Z) at position 19 (0): Unexpected character
    protected function parseDate(string $dateString): ?Carbon
    {
        // Handle unexpected character in seconds: "2004-01-28T00:03:010Z" -> "2004-01-28T00:03:10Z"
        if (preg_match('/T(\d{2}):(\d{2}):(\d{3})Z?$/', $dateString, $matches)) {
            $seconds = substr($matches[3], 0, 2); // Take first 2 digits of 3-digit seconds
            $dateString = str_replace($matches[0], "T{$matches[1]}:{$matches[2]}:{$seconds}" . (str_ends_with($matches[0], 'Z') ? 'Z' : ''), $dateString);
        }
        
        return parent::parseDate($dateString);
    }
}
