<?php

namespace DeadusefulWhoisParser\Parsers;

class Lu extends Generic
{
    protected array $matchNotFound = [
        '% No such domain',
    ];
}
