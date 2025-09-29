<?php

namespace Deaduseful\WhoisParser\Parsers;

class Kg extends Generic
{
    protected array $matchNotFound = [
        'Data not found. This domain is available for registration.',
    ];
}
