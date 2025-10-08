<?php

namespace Deaduseful\WhoisParser\Parsers;
class Ax extends Generic
{
    protected array $mapFields = [
        'modified' => self::DATE_UPDATED,
        'expires' => self::DATE_EXPIRY,
    ];
}

