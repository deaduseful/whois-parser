<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;
use Deaduseful\WhoisParser\Parsers\Traits\NotFoundByLookupContains;

class RegistrarCentralNic extends Generic
{
    use NotFoundByLookupContains;
    protected array $mapFields = [
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
        'domainStatus' => 'status',
    ];

    protected array $matchNotFound = [
        'The queried object does not exist',
        'No Object Found',
    ];

    protected array $matchReserved = [
        'Name Collision Reservation From ICANN',
    ];
}
