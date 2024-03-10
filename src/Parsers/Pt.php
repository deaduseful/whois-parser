<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Pt extends Generic
{
    protected ?string $dateFormat = 'd/m/Y H:i:s'; // eg: 28/02/2023 23:59:00
    protected array $mapFields = [
        'creationDate' => 'created',
        'expirationDate' => 'expiry',
    ];
}
