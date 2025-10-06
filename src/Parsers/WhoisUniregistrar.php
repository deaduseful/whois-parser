<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class WhoisUniregistrar extends Generic
{
    // Failed to parse time string (1987-03-04-T05:00:00Z) at position 10 (-): Unexpected character
    protected function parseDate(string $dateString): ?Carbon
    {
        if (empty($dateString)) {
            return null;
        }
        if (str_contains($dateString, '-T')) {
            // Fix the -T issue
            $dateString = str_replace('-T', 'T', $dateString);
        }
        return parent::parseDate($dateString);
    }
}
