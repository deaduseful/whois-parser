<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class Net extends Generic
{
    // Fixes: Could not parse '2009.07.22' and '1999.03.04 12:00:00': Double time specification
    protected function parseDate(string $dateString): ?Carbon
    {
        // Handle dot-separated dates: "2009.07.22" -> "2009-07-22"
        if (preg_match('/^(\d{4})\.(\d{2})\.(\d{2})(.*)$/', $dateString, $matches)) {
            $dateString = $matches[1] . '-' . $matches[2] . '-' . $matches[3] . $matches[4];
        }

        // Handle dot-separated date with time: "1999.03.04 12:00:00" -> "1999-03-04 12:00:00"
        if (preg_match('/^(\d{4})\.(\d{2})\.(\d{2}) (\d{2}:\d{2}:\d{2})$/', $dateString, $matches)) {
            $dateString = $matches[1] . '-' . $matches[2] . '-' . $matches[3] . ' ' . $matches[4];
        }

        return parent::parseDate($dateString);
    }
}
