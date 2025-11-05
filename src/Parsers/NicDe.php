<?php

namespace Deaduseful\WhoisParser\Parsers;

class NicDe extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        'free',
    ];

    protected string $statusField = 'status';
}
