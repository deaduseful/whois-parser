<?php

namespace DeadusefulWhoisParser\Parsers;

class Th extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'expDate' => 'expiry',
    ];
    protected array $matchNotFound = [
        '% No match found.',
    ];
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        return $lastElement === $this->matchNotFound[0];
    }
}
