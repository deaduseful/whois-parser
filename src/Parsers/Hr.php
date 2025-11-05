<?php

namespace Deaduseful\WhoisParser\Parsers;

class Hr extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        'No entries found',
    ];

    protected string $statusField = '%ERROR';
}
