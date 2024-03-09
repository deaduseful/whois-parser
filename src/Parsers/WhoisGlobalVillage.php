<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class WhoisGlobalVillage extends Generic
{
    protected function parseDate(string $dateString): ?Carbon
    {
        $dateString = str_replace('Z.0Z', 'Z', $dateString);
        return parent::parseDate($dateString);
    }
}
