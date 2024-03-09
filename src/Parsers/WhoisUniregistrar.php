<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisUniregistrar extends Generic
{
    protected ?string $dateFormat = 'Y-m-d\-\TH:i:s\Z'; // Failed to parse time string (1987-03-04-T05:00:00Z) at position 10 (-): Unexpected character
}
