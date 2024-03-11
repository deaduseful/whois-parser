<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Aw extends Generic
{
    protected array $matchNotFound = [
        'is free',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        foreach ($this->matchNotFound as $find) {
            if (str_contains($rawData[0], $find)) {
                return true;
            }
        }
        return false;
    }
}
