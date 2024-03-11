<?php

namespace Deaduseful\WhoisParser\src\Parsers;

// @todo map all fields correctly
use Carbon\Carbon;

class Kz extends Generic
{
    protected array $matchNotFound = [
        '*** Nothing found for this query.',
    ];
    protected array $mapFields = [
        'domainCreated' => 'created',
        'lastModified' => 'updated',
        'expires' => 'expiry',
    ];

    protected function parseDate(string $dateString): ?Carbon
    {
        $dateString = str_replace(['(', ')'], '', $dateString);
        return parent::parseDate($dateString);
    }
}
