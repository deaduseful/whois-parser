<?php

namespace Deaduseful\WhoisParser\Parsers;

class Eu extends Generic
{
    protected array $matchNotFound = [
        'AVAILABLE',
    ];
    protected string $statusField = 'status';
    protected array $matchInvalid = [
        '-7: %Invalid pattern',
    ];
}
