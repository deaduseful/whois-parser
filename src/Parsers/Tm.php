<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Tm extends Generic
{
    protected array $matchNotFound = [
        ' is available for purchase',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return str_ends_with($rawData[1], $this->matchNotFound[0]);
    }
}
