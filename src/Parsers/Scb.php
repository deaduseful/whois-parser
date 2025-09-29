<?php

namespace Deaduseful\WhoisParser\Parsers;

class Scb extends Generic
{
    protected array $matchNotFound = [
        'NOT FOUND',
    ];
    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
