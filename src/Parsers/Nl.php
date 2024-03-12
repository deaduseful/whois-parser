<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Nl extends Generic
{
    protected array $matchNotFound = [
        ' is free',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return str_ends_with($rawData[0], $this->matchNotFound[0]);
    }
}
