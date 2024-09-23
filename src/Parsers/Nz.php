<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Nz extends Generic
{
    protected array $matchInvalid = [
        'The domain name provided contains a hyphen in an invalid position',
    ];
}
