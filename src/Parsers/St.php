<?php

namespace Deaduseful\WhoisParser\Parsers;

class St extends Generic
{
    protected array $matchNotFound = [
        'No entries found for domain',
    ];

    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
