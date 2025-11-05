<?php

namespace Deaduseful\WhoisParser\Parsers;

class Lt extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        'available',
    ];

    protected string $statusField = 'status';
}
