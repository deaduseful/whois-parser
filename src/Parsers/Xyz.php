<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Xyz extends GenericCentralNic
{
    // Fixes: Could not parse '2023-04-26 08:57:22.0T00:00:00Z': DateTime::__construct(): Failed to parse time string (2023-04-26 08:57:22.0T00:00:00Z) at position 21 (T): Double time specification
    protected function parseDate(string $dateString): ?Carbon
    {
        $dateString = str_replace('T00:00:00Z', '', $dateString);
        return parent::parseDate($dateString);
    }
}
