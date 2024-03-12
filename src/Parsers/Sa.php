<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Sa extends Generic
{
    protected array $matchNotFound = [
        'No Match for',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        return str_starts_with($lastElement, $this->matchNotFound[0]);
    }
}
