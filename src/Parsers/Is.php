<?php

namespace Deaduseful\WhoisParser\Parsers;

class Is extends Generic
{
    use Traits\NotFoundByLookupContains;

    protected array $matchNotFound = [
        '% No entries found for query ',
    ];
}
