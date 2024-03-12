<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Uz extends Generic
{
    protected array $matchNotFound = [
        '% Domain names in the .uz domain can now be registered',
    ];
}
