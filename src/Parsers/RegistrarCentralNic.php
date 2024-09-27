<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class RegistrarCentralNic extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
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
}
