<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Au extends Generic
{
    protected array $matchNotFound = [
        'NOT FOUND',
    ];

    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
