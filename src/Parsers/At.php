<?php

namespace Deaduseful\WhoisParser\Parsers;

class At extends Generic
{
    protected array $matchNotFound = [
        '% nothing found',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastLine = array_pop($rawData);
        if (in_array($lastLine, $this->matchNotFound, true)) {
            return true;
        }
        return false;
    }
}
