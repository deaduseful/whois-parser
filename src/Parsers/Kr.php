<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Kr extends Generic
{
    protected ?string $dateFormat = 'Y. m. d.|'; // eg: 2024. 01. 27.
    protected array $mapFields = [
        'registeredDate' => 'created',
        'lastUpdatedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}
