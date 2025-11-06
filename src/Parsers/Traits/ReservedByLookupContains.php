<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait for parsers that detect "reserved" by checking if lookup text contains pattern
 * 
 * Prevents false positives by checking if actual domain registration data exists.
 * If the domain has 2+ key fields (created, expiry, nameservers, registrar), it's taken (not reserved).
 */
trait ReservedByLookupContains
{
    public function isReserved(): bool
    {
        foreach ($this->matchReserved as $find) {
            if (str_contains(strtolower($this->lookup), strtolower($find))) {
                // Check if we have actual registration data
                // Need at least 2 key fields to consider it a real registration
                $rawData = $this->parse();
                $fieldCount = 0;
                if (!empty($rawData['created'])) $fieldCount++;
                if (!empty($rawData['expiry'])) $fieldCount++;
                if (!empty($rawData['nameserver'])) $fieldCount++;
                if (!empty($rawData['registrar'])) $fieldCount++;
                
                if ($fieldCount >= 2) {
                    return false; // Has real registration data, so it's taken (not reserved)
                }
                
                return true;
            }
        }
        return false;
    }
}

