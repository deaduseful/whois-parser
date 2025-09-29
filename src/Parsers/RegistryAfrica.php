<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistryAfrica extends Generic
{
    protected array $matchNotFound = [
        'Available',
    ];

    protected array $matchReserved = [
        'Reserved:',
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
