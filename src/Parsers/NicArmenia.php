<?php

namespace DeadusefulWhoisParser\Parsers;

class NicArmenia extends Generic
{
    protected array $matchNotFound = [
        'No match',
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
