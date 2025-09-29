<?php

namespace DeadusefulWhoisParser\Parsers;

class Ax extends Generic
{
    protected array $matchNotFound = [
        'Domain not found',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        if (in_array($this->matchNotFound[0], $rawData)) {
            return true;
        }
        return false;
    }
}
