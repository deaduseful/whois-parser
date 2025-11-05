<?php

namespace Deaduseful\WhoisParser\Parsers;

class Be extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        'AVAILABLE',
    ];

    protected string $statusField = 'status';
}
