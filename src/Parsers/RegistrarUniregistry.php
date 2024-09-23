<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class RegistrarUniregistry extends Generic
{
    protected array $matchNotFound = [
        ' is available for registration',
    ];

    protected array $matchInvalid = [
        'Domain name violates registry policy',
    ];

    protected array $matchReserved = [
        'IANA Reserved',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[0])
            && str_ends_with($rawData[0], $this->matchNotFound[0]);
    }
}
