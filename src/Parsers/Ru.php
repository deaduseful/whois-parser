<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Ru extends Generic
{
    protected array $matchNotFound = [
        'No entries found for the selected source(s).',
    ];

    public function isNotFound(): bool
    {
        foreach ($this->matchNotFound as $find) {
            if (str_contains($this->lookup, $find)) {
                return true;
            }
        }
        return false;
    }
}
