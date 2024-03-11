<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Za extends Generic
{
    protected array $matchNotFound = [
        'Available',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        foreach ($rawData as $row) {
            if (in_array($row, $this->matchNotFound, true)) {
                return true;
            }
        }
        return false;
    }
}
