<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class RegistrarCentralNic extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
        'domainStatus' => 'status',
    ];

    protected array $matchNotFound = [
        'The queried object does not exist',
    ];

    protected array $matchReserved = [
        'Name Collision Reservation From ICANN',
    ];

    public function isNotFound(): bool
    {
        if (str_starts_with($this->lookup, $this->matchNotFound[0])) {
            return true;
        }
        return false;
    }

    // Fixes: Could not parse '2023-04-26 08:57:22.0T00:00:00Z': DateTime::__construct(): Failed to parse time string
    // (2023-04-26 08:57:22.0T00:00:00Z) at position 21 (T): Double time specification
    protected function parseDate(string $dateString): ?Carbon
    {
        // Handle double time specification: "2023-04-26 08:57:22.0T00:00:00Z"
        // Remove the duplicate time part after T
        if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}(?:\.\d+)?)T\d{2}:\d{2}:\d{2}Z?$/', $dateString, $matches)) {
            $dateString = $matches[1];
        }

        // Fallback for the specific case handled before
        $dateString = str_replace('T00:00:00Z', '', $dateString);

        return parent::parseDate($dateString);
    }
}
