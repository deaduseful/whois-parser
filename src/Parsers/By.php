<?php

namespace Deaduseful\WhoisParser\Parsers;

class By extends Generic
{
    use Traits\NotFoundByLookupStarts;

    protected array $matchNotFound = [
        'Object does not exist',
    ];
}
