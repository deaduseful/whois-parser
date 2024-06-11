<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Tn extends WhoisMediaServ
{
    protected array $mapFields = [
        'name' => 'nameserver',
        'creationDate' => 'created',
    ];
}
