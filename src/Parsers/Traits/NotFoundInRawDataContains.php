<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking if any raw data value contains pattern
 */
trait NotFoundInRawDataContains
{
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        foreach ($rawData as $value) {
            if (!is_string($value)) {
                continue;
            }
            foreach ($this->matchNotFound as $pattern) {
                if (str_contains($value, $pattern)) {
                    return true;
                }
            }
        }
        return false;
    }
}

