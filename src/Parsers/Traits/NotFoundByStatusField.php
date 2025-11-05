<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking the status field
 */
trait NotFoundByStatusField
{
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]) 
            && isset($this->matchNotFound[0]) 
            && $rawData[$this->statusField] === $this->matchNotFound[0];
    }
}

