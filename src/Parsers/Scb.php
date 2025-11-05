<?php

namespace Deaduseful\WhoisParser\Parsers;

class Scb extends Generic
{
    use Traits\NotFoundByLookupStarts;

    protected array $matchNotFound = [
        'NOT FOUND',
    ];
}
