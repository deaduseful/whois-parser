<?php

namespace DeadusefulWhoisParser\Parsers;

use Carbon\Carbon;

class Cl extends Generic
{
    protected array $matchNotFound = [
        'no entries found.',
    ];

    // Could not parse '2000-11-16 12:30:06 CLST': DateTime::__construct(): Failed to parse time string (2000-11-16 12:30:06 CLST) at position 20 (C): The timezone could not be found in the database
    protected function parseDate(string $dateString): ?Carbon
    {
        // @see https://en.wikipedia.org/wiki/Time_in_Chile#Summer_time_(CLST/EASST)
        $dateString = str_replace('CLST', 'GMT-3', $dateString);
        return parent::parseDate($dateString);
    }
}
