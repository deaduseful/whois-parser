<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking if last line starts with pattern
 */
trait NotFoundByLastLineStartsWith
{
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        if (count($rawData) > 0) {
            $lastElement = array_pop($rawData);
            if (is_string($lastElement)) {
                foreach ($this->matchNotFound as $pattern) {
                    if (str_starts_with($lastElement, $pattern)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
}

