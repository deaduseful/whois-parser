<?php

namespace Deaduseful\WhoisParser\Parsers;

class Hr extends Generic
{
    protected string $statusField = '%ERROR';

    protected array $matchNotFound = [
        'No entries found',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]) && $rawData[$this->statusField] === $this->matchNotFound[0];
    }
}
