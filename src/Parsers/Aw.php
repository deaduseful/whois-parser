<?php

namespace Deaduseful\WhoisParser\Parsers;

class Aw extends Generic
{
    protected array $matchNotFound = [
        ' is free',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        foreach ($this->matchNotFound as $find) {
            if (
                isset($rawData[0])
                && str_contains($rawData[0], $find)
            ) {
                return true;
            }
        }
        return false;
    }
}
