<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Su extends Generic
{
    protected array $mapFields = [
        'created' => 'created',
        'lastUpdatedOn' => 'updated',
        'nserver' => 'nameServer',
    ];

    protected function getLines(?string $lookup): array
    {
        $lookup = str_replace('Last updated on ', 'Last updated on:', $lookup);
        return parent::getLines($lookup);
    }
}
