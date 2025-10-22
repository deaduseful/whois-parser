<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class WhoisUniregistrar extends Generic
{
    // Fixes: Could not parse '1987-03-04-T05:00:00Z': Failed to parse time string (1987-03-04-T05:00:00Z) at position 10 (-): Unexpected character
    protected function parseDate(string $dateString): ?Carbon
    {
        if (empty($dateString)) {
            return null;
        }
        if (str_contains($dateString, '-T')) {
            $dateString = str_replace('-T', 'T', $dateString);
        }
        return parent::parseDate($dateString);
    }
}
