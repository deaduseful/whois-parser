<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Pl extends Generic
{
    protected ?string $dateFormat = 'Y.m.d H:i:s'; // Could not parse '1999.03.04 12:00:00': DateTime::__construct(): Failed to parse time string (1999.03.04 12:00:00) at position 11 (1): Double time specification

    protected array $mapFields = [
        'created' => 'created',
        'lastModified' => 'updated',
        'optionExpirationDate' => 'expiry',
    ];
}
