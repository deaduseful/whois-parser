<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Se extends Generic
{
    protected string $statusField = 'status';
    protected array $mapFields = [
        'nserver' => 'nameserver',
        'created' => 'created',
        'modified' => 'updated',
        'expires' => 'expiry',
        'registrar' => 'registrar',
        'status' => 'status',
        'dnssec' => 'dnssec',
        'holder' => 'registrar',
        'admin-c' => 'admin',
        'tech-c' => 'tech',
        'billing-c' => 'billing',
    ];
}
