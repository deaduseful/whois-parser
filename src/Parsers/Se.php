<?php

namespace Deaduseful\WhoisParser\Parsers;

class Se extends Generic
{
    protected string $statusField = 'status';
    protected array $mapFields = [
        'nserver' => 'nameserver',
        'created' => 'created',
        'modified' => 'updated',
        'expires' => 'expiry',
        'registrar' => 'registrar',
        'status' => 'status',
        'dnssec' => 'dnssec',
        'holder' => 'registrar',
        'admin-c' => 'admin',
        'tech-c' => 'tech',
        'billing-c' => 'billing',
        'registry-lock' => 'registryLock',
    ];

    protected array $matchNotFound = [
        ' not found.',
    ];
    
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        return is_string($lastElement)
            && str_ends_with($lastElement, $this->matchNotFound[0]);
    }

    /**
     * Sweden-specific transferability check
     * Checks "registry-lock" field status
     */
    public function isTransferable(array $rawData): ?bool
    {
        // Check registry-lock field (check unlocked FIRST as "unlocked" contains "locked")
        if (isset($rawData['registryLock'])) {
            $lockStatus = strtolower($rawData['registryLock']);
            if (str_contains($lockStatus, 'unlocked')) {
                return true;
            }
            if (str_contains($lockStatus, 'locked')) {
                return false;
            }
        }
        
        // Fall back to parent implementation
        return parent::isTransferable($rawData);
    }
}
