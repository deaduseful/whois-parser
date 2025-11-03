<?php

namespace Deaduseful\WhoisParser\Parsers;

class Tr extends Generic
{
    protected array $mapFields = [
        'createdOn' => 'created',
        'lastUpdateTime' => 'updated',
        'expiresOn' => 'expiry',
        'transferStatus' => 'transferStatus',
    ];
    protected array $matchNotFound = [
        'No match found for ',
    ];

    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }

    /**
     * Turkey-specific transferability check
     * Checks "Transfer Status" field for LOCKED
     */
    public function isTransferable(array $rawData): ?bool
    {
        // Check Transfer Status field
        if (isset($rawData['transferStatus'])) {
            $transferStatus = strtolower($rawData['transferStatus']);
            if (str_contains($transferStatus, 'locked')) {
                return false;
            }
        }
        
        // Fall back to parent implementation
        return parent::isTransferable($rawData);
    }
}
