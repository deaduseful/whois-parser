<?php

namespace Deaduseful\WhoisParser\Parsers;

class St extends Generic
{
    use Traits\NotFoundByLookupStarts;

    protected array $matchNotFound = [
        'No entries found for domain',
    ];
}
