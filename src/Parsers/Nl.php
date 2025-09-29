<?php

namespace Deaduseful\WhoisParser\Parsers;

class Nl extends Generic
{
    protected array $matchNotFound = [
        ' is free',
    ];

    protected array $matchInvalid = [
        'Error: invalid domain name',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[0])
            && str_ends_with($rawData[0], $this->matchNotFound[0]);
    }
}
