<?php

namespace Deaduseful\WhoisParser\Parsers;

class Ye extends Generic
{
    use Traits\NotFoundByLookupStarts;

    protected array $matchNotFound = [
        'NOT FOUND',
    ];
}
