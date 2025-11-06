<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistryIndia;

/**
 * Detects India Registry format (.in and Indian TLDs)
 * 
 * Characteristics:
 * - India-specific formatting
 * - "Domain name matches a restricted word"
 * - Indian language TLDs
 */
class IndiaRegistryDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 90;
    protected const NAME = 'India Registry';
    
    // India Registry-specific patterns (not just abbreviations)
    protected const INDICATORS = [
        'registry.in',
        'National Internet Exchange of India',
        'Domain name matches a restricted word',
        'This name is not available for registration',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Removed "NIXI" alone - too short (4 letters)
        // Other patterns are specific enough
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistryIndia::class;
        }
        
        return null;
    }
}

