<?php

namespace Deaduseful\WhoisParser\Parsers\Traits;

/**
 * Trait providing comprehensive "not found" pattern matching
 * 
 * This trait implements advanced pattern matching logic for detecting "not found" domains.
 * It expects the class using it to have a $matchNotFound property with common patterns.
 * 
 * Usage: Include this trait in parser classes that need comprehensive pattern detection.
 */
trait CommonNotFoundPatterns
{

    /**
     * Check if domain is not found by searching for common patterns
     * 
     * Checks both raw lookup text and parsed data for not found indicators.
     * Falls back to parent implementation if available.
     */
    public function isNotFound(): bool
    {
        // Use the patterns from $matchNotFound property
        $patterns = $this->matchNotFound ?? [];
        
        // First check if lookup contains any not found patterns
        foreach ($patterns as $find) {
            if (str_contains($this->lookup, $find)) {
                // Special handling for "available for registration" to avoid false positives
                if ($find === 'available for registration') {
                    $position = strpos($this->lookup, $find);
                    // Get context around the pattern to check for negation
                    $contextStart = max(0, $position - 50);
                    $contextLength = min(150, strlen($this->lookup) - $contextStart);
                    $context = strtolower(substr($this->lookup, $contextStart, $contextLength));
                    
                    // Skip if it says "NOT available" or "under certain conditions"
                    if (str_contains($context, 'not available') 
                        || str_contains($context, 'under certain conditions')
                        || str_contains($context, 'only available for registration under')) {
                        continue;
                    }
                    
                    // Only match if it appears early (not in boilerplate)
                    if ($position < 500) {
                        return true;
                    }
                    continue;
                }
                return true;
            }
        }
        
        // Then check parsed data
        $rawData = $this->parse();
        foreach ($patterns as $find) {
            // Skip "available for registration" in parsed data (handled above in lookup check)
            if ($find === 'available for registration') {
                continue;
            }
            
            if (in_array($find, $rawData, true)) {
                return true;
            }
            if (
                isset($rawData[0])
                && str_contains($rawData[0], $find)
            ) {
                return true;
            }
        }
        if (empty($rawData['notFound']) === false) {
            return true;
        }
        if (
            isset($rawData[$this->statusField])
            && in_array($rawData[$this->statusField], $patterns, true)
        ) {
            return true;
        }
        
        return parent::isNotFound();
    }
}

