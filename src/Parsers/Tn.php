<?php

namespace Deaduseful\WhoisParser\Parsers;

class Tn extends WhoisMediaServ
{
    protected array $mapFields = [
        'name' => 'nameserver',
        'creationDate' => 'created',
    ];
}
