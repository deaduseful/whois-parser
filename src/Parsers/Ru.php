<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Ru extends Generic
{
    protected array $mapFields = [
        'created' => 'created',
        'lastUpdatedOn' => 'updated',
        'freeDate' => 'expiry',
        'nserver' => 'nameServer',
    ];

    protected function parseLines(?string $lookup): array
    {
        $lookup = str_replace('Last updated on ', 'Last updated on:', $lookup);
        return parent::parseLines($lookup);
    }

    protected array $matchNotFound = [
        'No entries found for the selected source(s).',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[1])
            && $rawData[1] === $this->matchNotFound[0];
    }
}
