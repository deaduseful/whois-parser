<?php

namespace DeadusefulWhoisParser\Parsers;

class Pl extends Generic
{
    protected ?string $dateFormat = 'Y.m.d H:i:s'; // Could not parse '1999.03.04 12:00:00': DateTime::__construct(): Failed to parse time string (1999.03.04 12:00:00) at position 11 (1): Double time specification

    protected array $mapFields = [
        'created' => 'created',
        'lastModified' => 'updated',
        'optionExpirationDate' => 'expiry',
    ];

    protected array $matchNotFound = [
        'No information available about domain name',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[1])
            && str_starts_with($rawData[1], $this->matchNotFound[0]);
    }
}
