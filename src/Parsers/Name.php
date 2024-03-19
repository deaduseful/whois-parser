<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Name extends Generic
{
    protected array $matchNotFound = [
        'No match for ',
    ];
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        foreach ($rawData as $line) {
            if (is_string($line) && str_starts_with($line, $this->matchNotFound[0])) {
                return true;
            }
        }
        return false;
    }
}
