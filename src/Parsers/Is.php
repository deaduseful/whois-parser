<?php

namespace Deaduseful\WhoisParser\Parsers;

class Is extends Generic
{
    protected array $matchNotFound = [
        '% No entries found for query ',
    ];

    public function isNotFound(): bool
    {
        // Check the raw WHOIS response for "not found" message
        foreach ($this->matchNotFound as $find) {
            if (str_contains($this->lookup, $find)) {
                return true;
            }
        }
        
        // Fall back to parent implementation
        return parent::isNotFound();
    }
}
