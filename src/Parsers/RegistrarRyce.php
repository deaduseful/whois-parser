<?php

namespace DeadusefulWhoisParser\Parsers;

class RegistrarRyce extends Generic
{
    protected array $matchNotFound = [
        'Available',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        if (in_array($this->matchNotFound[0], $rawData, true)) {
            return true;
        }
        return false;
    }
}
