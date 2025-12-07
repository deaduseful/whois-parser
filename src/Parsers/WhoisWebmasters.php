<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class WhoisWebmasters extends Generic
{
    // Fixes: Could not parse '2019-15-03T19:35:08Z': Failed to parse time string
    // Only the Updated Date uses Y-d-m format, other dates use Y-m-d format
    protected function parseDate(string $dateString): ?Carbon
    {
        $dateString = trim($dateString);
        
        // Check if this date matches the Y-d-m format (year-day-month)
        // Pattern: YYYY-DD-MM where DD > 12 (indicating it's day, not month)
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T/', $dateString, $matches)) {
            $first = (int)$matches[2];
            $second = (int)$matches[3];
            
            // If first number > 12, it's Y-d-m format (year-day-month)
            if ($first > 12 && $second <= 12) {
                try {
                    return Carbon::createFromFormat('Y-d-m\TH:i:s\Z', $dateString);
                } catch (\Exception $e) {
                    // Fall through to parent parsing
                }
            }
        }
        
        // Use parent parsing for standard Y-m-d dates
        return parent::parseDate($dateString);
    }
}
