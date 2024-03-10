<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Ro extends Generic
{
    protected array $mapFields = [
        'registeredOn' => 'created',
        'expiresOn' => 'expiry',
    ];

    protected function parseDate(string $dateString): ?Carbon
    {
        if (str_starts_with(strtolower($dateString), 'before')) {
            return null;
        }
        return parent::parseDate($dateString);
    }
}
