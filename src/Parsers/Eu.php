<?php

namespace Deaduseful\WhoisParser\Parsers;

class Eu extends Generic
{
    protected array $matchNotFound = [
        'AVAILABLE',
    ];

    protected array $matchInvalid = [
        '-7: %Invalid pattern',
    ];

    protected string $statusField = 'status';
}
