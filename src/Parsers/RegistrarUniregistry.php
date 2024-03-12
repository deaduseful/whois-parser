<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class RegistrarUniregistry extends Generic
{
    protected array $matchNotFound = [
        ' is available for registration',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return str_ends_with($rawData[0], $this->matchNotFound[0]);
    }
}
