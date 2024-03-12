<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class GenericArpa extends Generic
{
    protected array $matchNotFound = [
        '% This query returned 0 objects.',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        if (in_array($rawData[1], $this->matchNotFound, true)) {
            return true;
        }
        return false;
    }
}
