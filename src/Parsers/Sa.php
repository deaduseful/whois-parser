<?php

namespace Deaduseful\WhoisParser\Parsers;

class Sa extends Generic
{
    use Traits\NotFoundByLastLineStartsWith;

    protected array $matchNotFound = [
        'No Match for',
    ];
}
