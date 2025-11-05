<?php

namespace Deaduseful\WhoisParser\Parsers;

class Cr extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        '101: no entries found',
    ];

    protected string $statusField = '%ERROR';
}
