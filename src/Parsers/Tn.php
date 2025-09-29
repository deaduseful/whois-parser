<?php

namespace DeadusefulWhoisParser\Parsers;

class Tn extends WhoisMediaServ
{
    protected array $mapFields = [
        'name' => 'nameserver',
        'creationDate' => 'created',
    ];
}
