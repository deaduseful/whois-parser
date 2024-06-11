<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class WhoisIana extends Generic
{
    protected string $statusField = 'status';

    protected array $mapFields = [
        'changed' => 'updated',
    ];
}
