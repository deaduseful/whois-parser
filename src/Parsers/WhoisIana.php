<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisIana extends Generic
{

    protected string $statusField = 'status';

    protected array $mapFields = [
        'changed' => 'updated',
    ];
}
