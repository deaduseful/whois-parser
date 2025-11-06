<?php

namespace Deaduseful\WhoisParser\Parsers;

use Deaduseful\WhoisParser\Parsers\Traits\NotFoundByDomainData;

class WhoisTcinetRu extends Generic
{
    use NotFoundByDomainData;
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
}
