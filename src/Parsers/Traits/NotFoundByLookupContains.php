<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking if lookup text contains pattern
 */
trait NotFoundByLookupContains
{
    public function isNotFound(): bool
    {
        foreach ($this->matchNotFound as $pattern) {
            if (str_contains($this->lookup, $pattern)) {
                return true;
            }
        }
        return false;
    }
}

