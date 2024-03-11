<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Tv extends Generic
{
    protected array $matchInvalid = [
        'Reserved Domain Name',
    ];
}
