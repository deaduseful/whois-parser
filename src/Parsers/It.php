<?php

namespace Deaduseful\WhoisParser\Parsers;

class It extends Generic
{
    protected array $mapFields = [
        'created' => 'created',
        'lastUpdate' => 'updated',
        'expireDate' => 'expiry',
    ];

    protected string $statusField = 'status';

    protected array $matchNotFound = [
        'AVAILABLE',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]) && $rawData[$this->statusField] === $this->matchNotFound[0];
    }
}
