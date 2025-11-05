<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking the last line of parsed data
 */
trait NotFoundByLastLine
{
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        if (count($rawData) > 0) {
            $lastLine = end($rawData);
            if (in_array($lastLine, $this->matchNotFound, true)) {
                return true;
            }
        }
        return false;
    }
}

