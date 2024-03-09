<?php

namespace Deaduseful\WhoisParser\src\Parsers;

// @todo map all fields correctly
use Carbon\Carbon;

class Kz extends Generic
{
    protected array $mapFields = [
        'domainCreated' => 'created',
        'lastModified' => 'updated',
        'expires' => 'expires',
    ];

    protected function parseDate(string $dateString): ?Carbon
    {
        $dateString = str_replace(['(', ')'], '', $dateString);
        return parent::parseDate($dateString);
    }
}
