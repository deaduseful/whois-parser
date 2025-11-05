<?php

namespace Deaduseful\WhoisParser\Parsers;

class Ly extends Generic
{
    protected array $matchNotFound = [
        'domainStatus',
        'Not Found',
    ];

    protected string $statusField = 'domainStatus';

    protected array $mapFields = [
        'created' => 'created',
        'updated' => 'updated',
        'expired' => 'expiry',
    ];
}
