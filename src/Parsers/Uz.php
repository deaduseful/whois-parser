<?php

namespace Deaduseful\WhoisParser\Parsers;

class Uz extends Generic
{
    use Traits\NotFoundByLookupContains;

    protected array $matchNotFound = [
        'not found in database',
    ];
}
