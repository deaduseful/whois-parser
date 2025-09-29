<?php

namespace DeadusefulWhoisParser\Parsers;

class WhoisAudaOrgAu extends RegistrarIdentityDigital
{
    protected array $matchNotFound = [
        'Domain not found.',
        'NOT FOUND',
    ];

    public function isNotFound(): bool
    {
        foreach ($this->matchNotFound as $row) {
            if (str_starts_with($this->lookup, $row)) {
                return true;
            }
        }
        return false;
    }
}
