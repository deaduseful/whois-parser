<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Sb extends Generic
{
    protected array $matchInvalid = [
        'This string is on a restricted list',
        'This domain is on a restricted list',
    ];
}
