<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class It extends Generic
{
    protected array $mapFields = [
        'created' => 'created',
        'lastUpdate' => 'updated',
        'expireDate' => 'expiry',
    ];

    protected function getFilter(array $raw): array
    {
        // @todo handle dates better.
    }
}
