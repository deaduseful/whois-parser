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

    protected array $matchNotFound = [
        ' not found.',
    ];
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        return is_string($lastElement)
            && str_ends_with($lastElement, $this->matchNotFound[0]);
    }
}
