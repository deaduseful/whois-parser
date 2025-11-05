<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistrarSidn extends Generic
{
    protected array $matchNotFound = [
        'free',
    ];

    protected array $matchInvalid = [
        'Error: invalid domain name',
    ];
}
