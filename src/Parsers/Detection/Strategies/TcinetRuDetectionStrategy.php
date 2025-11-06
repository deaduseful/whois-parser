<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\WhoisTcinetRu;

/**
 * Detects TCI Whois format (Russian registry)
 * 
 * Characteristics:
 * - "% TCI Whois Service"
 * - Russian language notices
 * - "Last updated on" format (different from Identity Digital)
 * - Used for .ru, .su and Russian IDN TLDs
 */
class TcinetRuDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 15; // Check before Identity Digital
    protected const NAME = 'TCI Whois (Russian Registry)';
    
    protected const INDICATORS = [
        '% TCI Whois Service',
    ];
    
    public function detect(string $lookup): ?string
    {
        // TCI has very specific header
        if ($this->containsIndicators($lookup, self::INDICATORS, 2)) {
            return WhoisTcinetRu::class;
        }
        
        return null;
    }
}

