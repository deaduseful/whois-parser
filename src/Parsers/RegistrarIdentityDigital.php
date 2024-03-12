<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class RegistrarIdentityDigital extends Generic
{
    protected array $mapFields = [
        'createdDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
    ];
    protected array $matchNotFound = [
        'Domain not found.',
    ];
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        foreach ($rawData as $row) {
            if (in_array($row, $this->matchNotFound, true)) {
                return true;
            }
        }
        return false;
    }
}
