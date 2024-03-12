<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Lv extends Generic
{
    protected string $statusField = 'status';

    protected array $matchNotFound = [
        'free',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]) && $rawData[$this->statusField] === $this->matchNotFound[0];
    }
}
