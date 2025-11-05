<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking the first line of parsed data
 */
trait NotFoundByFirstLine
{
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        if (isset($rawData[0])) {
            if (in_array($rawData[0], $this->matchNotFound, true)) {
                return true;
            }
        }
        return false;
    }
}

