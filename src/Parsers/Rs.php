<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Rs extends Generic
{
    protected array $mapFields = [
        'registrationDate' => 'created',
        'modificationDate' => 'updated',
        'expirationDate' => 'expiry',
    ];

    protected array $matchNotFound = [
        '103: Domain is not registered',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData['%ERROR'])
            && $rawData['%ERROR'] === $this->matchNotFound[0];
    }
}
