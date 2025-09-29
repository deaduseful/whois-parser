<?php

namespace DeadusefulWhoisParser\Parsers;

class Tw extends Generic
{
    protected array $matchNotFound = [
        'No Found',
    ];

    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
