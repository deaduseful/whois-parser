<?php

namespace Deaduseful\WhoisParser\Parsers;

class Bo extends Generic
{
    protected array $matchNotFound = [
        'DOMINIO NO REGISTRADO',
    ];
    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}
