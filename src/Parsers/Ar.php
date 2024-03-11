<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Ar extends Generic
{
    protected array $matchNotFound = [
        'El dominio no se encuentra registrado en NIC Argentina',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        if (in_array($rawData[0], $this->matchNotFound, true)) {
            return true;
        }
        return false;
    }
}
