<?php

namespace Deaduseful\WhoisParser\Parsers;

class Lt extends Generic
{
    protected string $statusField = 'status';

    protected array $matchNotFound = [
        'available',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]) && $rawData[$this->statusField] === $this->matchNotFound[0];
    }
}
