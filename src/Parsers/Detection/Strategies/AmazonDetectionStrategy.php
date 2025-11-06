<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarAmazon;

/**
 * Detects Amazon Registry format
 * 
 * Characteristics:
 * - Amazon-specific patterns
 * - AWS/Amazon branding
 */
class AmazonDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 100;
    protected const NAME = 'Amazon Registry';
    
    // Amazon Registry-specific WHOIS patterns (not just brand mentions)
    protected const INDICATORS = [
        'Amazon Registry Services, Inc.',
        'amazonregistry.com',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Now requires full "Amazon Registry Services, Inc." or their domain
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistrarAmazon::class;
        }
        
        return null;
    }
}

