<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Uz extends Generic
{
    protected array $matchNotFound = [
        'not found in database',
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
