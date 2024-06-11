<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisMediaServ extends Generic
{
    protected array $matchNotFound = [
        'NO OBJECT FOUND!',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return in_array($this->matchNotFound[0], $rawData);
    }
}
