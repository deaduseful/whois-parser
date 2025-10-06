<?php

namespace Deaduseful\WhoisParser\Parsers;

class Im extends Generic
{
    protected array $matchNotFound = [
        ' was not found.',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        if (is_string($lastElement)) {
            return str_ends_with($lastElement, $this->matchNotFound[0]);
        }
        return false;
    }
}
