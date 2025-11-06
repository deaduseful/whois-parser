<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "not found" by checking if actual domain data exists
 * 
 * Prevents false positives where "not found" patterns appear but domain has registration data.
 * Checks for key registration fields (created date, expiry, nameservers, registrar).
 */
trait NotFoundByDomainData
{
    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        
        // Check if we have actual domain data
        // If we have key registration fields, the domain exists
        $hasDomainData = !empty($rawData['created']) 
            || !empty($rawData['domainName'])
            || !empty($rawData['nameserver'])
            || !empty($rawData['registrar'])
            || !empty($rawData['registryDomainID']);
        
        if ($hasDomainData) {
            return false; // Domain has data, so it exists
        }
        
        // Check for "not found" patterns in parsed data
        foreach ($rawData as $row) {
            if (is_string($row) && in_array($row, $this->matchNotFound, true)) {
                return true;
            }
        }
        
        // Fallback to checking if there's no updated date
        return $this->getUpdated($rawData) === null;
    }
}

