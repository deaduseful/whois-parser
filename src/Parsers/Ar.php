<?php

namespace Deaduseful\WhoisParser\Parsers;

class Ar extends Generic
{
    use Traits\NotFoundByFirstLine;

    protected array $matchNotFound = [
        'El dominio no se encuentra registrado en NIC Argentina',
    ];
}
