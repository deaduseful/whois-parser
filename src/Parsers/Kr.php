<?php

namespace Deaduseful\WhoisParser\Parsers;

class Kr extends Generic
{
    use Traits\NotFoundInRawDataContains;

    protected array $matchNotFound = [
        'The requested domain was not found',
    ];

    protected ?string $dateFormat = 'Y. m. d.|';

    protected array $mapFields = [
        'registeredDate' => 'created',
        'lastUpdatedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
