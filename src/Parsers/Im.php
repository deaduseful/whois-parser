<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Im extends Generic
{
    protected array $matchNotFound = [
        ' was not found.',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        return str_ends_with($lastElement, $this->matchNotFound[0]);
    }
}
