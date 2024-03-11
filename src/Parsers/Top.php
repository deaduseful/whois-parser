<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Top extends Generic
{
    protected array $matchInvalid = [
        'Name Collision Reservation From ICANN',
    ];
}
