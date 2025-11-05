<?php

namespace Deaduseful\WhoisParser\Parsers;

class It extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        'AVAILABLE',
    ];

    protected string $statusField = 'status';

    protected array $mapFields = [
        'created' => 'created',
        'lastUpdate' => 'updated',
        'expireDate' => 'expiry',
    ];
}
