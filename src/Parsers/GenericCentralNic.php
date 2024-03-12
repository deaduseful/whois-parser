<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class GenericCentralNic extends Generic
{
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
    ];
    protected array $matchInvalid = [
        'Name Collision Reservation From ICANN',
    ];
    protected array $matchNotFound = [
        'The queried object does not exist',
    ];

    public function isNotFound(): bool
    {
        if (str_starts_with($this->lookup, $this->matchNotFound[0])) {
            return true;
        }
        return false;
    }
}
