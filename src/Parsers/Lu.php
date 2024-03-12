<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Lu extends Generic
{
    protected array $matchNotFound = [
        '% No such domain',
    ];
}
