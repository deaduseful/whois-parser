<?php

namespace Deaduseful\WhoisParser\Parsers;

class Bg extends Generic
{
    use Traits\NotFoundByStatusField;

    protected array $matchNotFound = [
        'available',
    ];

    protected string $statusField = 'registrationStatus';
}
