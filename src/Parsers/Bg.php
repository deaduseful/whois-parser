<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Bg extends Generic
{
    protected string $statusField = 'registrationStatus';

    protected array $matchNotFound = [
        'available',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]) && $rawData[$this->statusField] === $this->matchNotFound[0];
    }
}
