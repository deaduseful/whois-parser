<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class WhoisOne extends Generic
{
    protected function parseDate(string $dateString): ?Carbon
    {
        $dateString = str_replace('Z[UTC]', 'Z', $dateString);
        return parent::parseDate($dateString);
    }
}
