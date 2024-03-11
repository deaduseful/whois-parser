<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Pk extends Generic
{
    protected array $matchNotFound = [];
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData['available']) && $rawData['available'] === 'Yes.';
    }
}
