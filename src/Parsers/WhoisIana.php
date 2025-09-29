<?php

namespace DeadusefulWhoisParser\Parsers;

use Carbon\Carbon;

class WhoisIana extends Generic
{
    protected string $statusField = 'status';

    protected array $mapFields = [
        'changed' => 'updated',
    ];
}
