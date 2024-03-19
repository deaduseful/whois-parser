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
        if (isset($rawData[15])
            && $rawData[15] === $this->matchNotFound[0]) {
            return true;
        }
        return false;
    }
}
