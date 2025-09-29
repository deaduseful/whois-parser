<?php

namespace DeadusefulWhoisParser\Parsers;

use Carbon\Carbon;

class Br extends Generic
{
    protected ?string $dateFormat = 'Ymd|'; // Could not parse '20220803 #24844081': DateTime::__construct(): Failed to parse time string (20220803 #24844081) at position 9 (#): Unexpected character
    protected array $mapFields = [
        'created' => 'created',
        'changed' => 'updated',
        'expires' => 'expiry',
    ];

    protected function parseDate(string $dateString): ?Carbon
    {
        if ($pos = strpos($dateString, '#')) {
            $dateString = trim(substr($dateString, 0, $pos));
        }
        return parent::parseDate($dateString);
    }

    protected array $matchNotFound = [
        '% No match for',
    ];

    public function isNotFound(): bool
    {
        foreach ($this->matchNotFound as $find) {
            if (str_contains($this->lookup, $find)) {
                return true;
            }
        }
        return false;
    }
}
