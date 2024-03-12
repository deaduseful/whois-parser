<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class By extends Generic
{
    protected array $matchNotFound = [
        'Object does not exist',
    ];
    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
