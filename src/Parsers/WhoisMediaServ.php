<?php

namespace Deaduseful\WhoisParser\Parsers;

class WhoisMediaServ extends Generic
{
    protected array $matchNotFound = [
        'NO OBJECT FOUND!',
    ];

    protected array $mapFields = [
        'nameserver' => 'nameserver',
        'changed' => 'updated',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->getParsed();
        $notFound = in_array($this->matchNotFound[0], $rawData);
        if ($notFound && $this->getNameservers() === null) {
            return true;
        }
        return false;
    }
}
