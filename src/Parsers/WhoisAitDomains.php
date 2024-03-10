<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class WhoisAitDomains extends Generic
{
    protected ?string $dateFormat = 'Y-m-d\TH:i:s\0\Z'; // Failed to parse time string (2004-01-28T00:03:010Z) at position 19 (0): Unexpected character
}
