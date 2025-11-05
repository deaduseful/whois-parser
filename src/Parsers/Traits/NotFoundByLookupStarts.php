<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking if lookup text starts with pattern
 */
trait NotFoundByLookupStarts
{
    public function isNotFound(): bool
    {
        foreach ($this->matchNotFound as $pattern) {
            if (str_starts_with($this->lookup, $pattern)) {
                return true;
            }
        }
        return false;
    }
}

