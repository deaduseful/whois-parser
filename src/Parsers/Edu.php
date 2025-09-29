<?php

namespace Deaduseful\WhoisParser\Parsers;

class Edu extends Generic
{
    protected string $statusField = 'nOMATCH';

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[$this->statusField]);
    }
}
