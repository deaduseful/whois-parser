<?php

namespace Deaduseful\WhoisParser\Parsers;

class At extends Generic
{
    use Traits\NotFoundByLastLine;

    protected array $matchNotFound = [
        '% nothing found',
    ];
}
