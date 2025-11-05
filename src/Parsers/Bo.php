<?php

namespace Deaduseful\WhoisParser\Parsers;

class Bo extends Generic
{
    use Traits\NotFoundByLookupStarts;

    protected array $matchNotFound = [
        'DOMINIO NO REGISTRADO',
    ];
}
