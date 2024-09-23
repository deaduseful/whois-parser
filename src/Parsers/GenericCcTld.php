<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class GenericCcTld extends Generic
{
    protected array $matchNotFound = [
        'domainStatus' => 'No Object Found',
    ];
    protected array $matchInvalid = [
        'Prohibited String - Domain Cannot Be Registered',
    ];
    protected string $statusField = 'domainStatus';
    protected array $mapFields = [
        'creationDate' => 'created',
        'updated' => 'updated',
        'expired' => 'expiry',
    ];
}
