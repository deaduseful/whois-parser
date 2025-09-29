<?php

namespace DeadusefulWhoisParser\Parsers;

class WhoisFreenoms extends Generic
{
    protected array $matchNotFound = [
        'The domain you requested is not known in Freenoms database.',
    ];
}
