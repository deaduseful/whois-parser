<?php

namespace Deaduseful\WhoisParser\Parsers;

class Nz extends Generic
{
    protected array $matchInvalid = [
        'The domain name provided contains a hyphen in an invalid position',
    ];
}
