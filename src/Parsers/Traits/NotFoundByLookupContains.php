<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking if lookup text contains pattern
 * 
 * This trait checks the raw lookup text for "not found" patterns, then falls back to 
 * parent implementation if no match is found. This allows parsers to add their own 
 * specific patterns while still benefiting from parent class logic.
 */
trait NotFoundByLookupContains
{
    public function isNotFound(): bool
    {
        // Check if the lookup contains any of the not found patterns
        foreach ($this->matchNotFound as $pattern) {
            if (str_contains($this->lookup, $pattern)) {
                return true;
            }
        }
        return parent::isNotFound();
    }
}

