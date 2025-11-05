<?php

namespace Deaduseful\WhoisParser\Parsers;

class Tw extends Generic
{
    use Traits\NotFoundByLookupStarts;

    protected array $matchNotFound = [
        'No Found',
    ];
}
