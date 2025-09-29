<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class Com extends Generic
{
    // Fixes: Could not parse '1987-03-04-T05:00:00Z': Failed to parse time string (1987-03-04-T05:00:00Z) at position 10 (-): Unexpected character
    protected function parseDate(string $dateString): ?Carbon
    {
        // Handle hyphen before T: "1987-03-04-T05:00:00Z" -> "1987-03-04T05:00:00Z"
        $dateString = str_replace('-T', 'T', $dateString);
        
        return parent::parseDate($dateString);
    }
}
