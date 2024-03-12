<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Cr extends Generic
{
    protected string $statusField = '%ERROR';

    protected array $matchNotFound = [
        '101: no entries found',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]) && $rawData[$this->statusField] === $this->matchNotFound[0];
    }
}
