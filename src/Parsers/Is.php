<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Is extends Generic
{
    protected array $matchNotFound = [
        '% No entries found for query ',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        return str_starts_with($lastElement, $this->matchNotFound[0]);
    }
}
