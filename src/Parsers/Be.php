<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Be extends Generic
{
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
