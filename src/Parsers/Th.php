<?php

namespace Deaduseful\WhoisParser\Parsers;

class Th extends Generic
{
    use Traits\NotFoundByLastLine;

    protected array $matchNotFound = [
        '% No match found.',
    ];

    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'expDate' => 'expiry',
    ];
}
