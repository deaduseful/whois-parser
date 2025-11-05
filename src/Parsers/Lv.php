<?php

namespace Deaduseful\WhoisParser\Parsers;

class Lv extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        'free',
    ];

    protected string $statusField = 'status';
}
