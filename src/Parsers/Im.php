<?php

namespace Deaduseful\WhoisParser\Parsers;

class Im extends Generic
{
    use Traits\NotFoundByLastLineEndsWith;

    protected array $matchNotFound = [
        ' was not found.',
    ];
}
