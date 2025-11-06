<?php

namespace Deaduseful\WhoisParser\Parsers\Detection;

/**
 * Abstract base class for detection strategies
 * Provides common functionality for pattern matching
 */
abstract class AbstractDetectionStrategy implements DetectionStrategy
{
    protected int $priority = 100;
    protected const NAME = 'Unknown Strategy';
    
    public function getPriority(): int
    {
        return $this->priority;
    }
    
    public function getName(): string
    {
        return static::NAME;
    }
    
    /**
     * Check if any of the given indicators are present in the lookup text
     * 
     * @param string $lookup The WHOIS response
     * @param array $indicators Array of string patterns to search for
     * @param int $minMatches Minimum number of matches required (default: 1)
     * @param bool $caseSensitive Whether to use case-sensitive matching
     * @return bool True if enough indicators match
     */
    protected function containsIndicators(
        string $lookup,
        array $indicators,
        int $minMatches = 1,
        bool $caseSensitive = true
    ): bool {
        $matchCount = 0;
        
        foreach ($indicators as $indicator) {
            $found = $caseSensitive 
                ? str_contains($lookup, $indicator)
                : stripos($lookup, $indicator) !== false;
                
            if ($found) {
                $matchCount++;
                if ($matchCount >= $minMatches) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if all of the given indicators are present in the lookup text
     * 
     * @param string $lookup The WHOIS response
     * @param array $indicators Array of string patterns to search for
     * @param bool $caseSensitive Whether to use case-sensitive matching
     * @return bool True if all indicators match
     */
    protected function containsAllIndicators(
        string $lookup,
        array $indicators,
        bool $caseSensitive = true
    ): bool {
        foreach ($indicators as $indicator) {
            $found = $caseSensitive 
                ? str_contains($lookup, $indicator)
                : stripos($lookup, $indicator) !== false;
                
            if (!$found) {
                return false;
            }
        }
        
        return true;
    }
}

