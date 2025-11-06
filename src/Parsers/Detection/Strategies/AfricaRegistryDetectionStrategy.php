<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistryAfrica;

/**
 * Detects Africa Registry format (.africa, .capetown, .durban, .joburg, .za)
 * 
 * Characteristics:
 * - "Available" status
 * - Africa-specific formatting
 */
class AfricaRegistryDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 80;
    protected const NAME = 'Africa Registry';
    
    // Africa Registry-specific patterns (not TLD mentions)
    protected const INDICATORS = [
        'ZA Central Registry',
        'registry.net.za',
        'afriregistry',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Primary: Check for "Available" as exact status (very specific to .za format)
        if (preg_match('/Status:\s*Available/i', $lookup)) {
            return RegistryAfrica::class;
        }
        
        // Secondary: Check for Africa Registry specific infrastructure
        // Removed TLD mentions like ".africa" - those could appear anywhere
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistryAfrica::class;
        }
        
        return null;
    }
}

