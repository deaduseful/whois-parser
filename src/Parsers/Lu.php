<?php

namespace Deaduseful\WhoisParser\Parsers;

class Lu extends Generic
{
    protected array $matchNotFound = [
        '% No such domain',
    ];
}
