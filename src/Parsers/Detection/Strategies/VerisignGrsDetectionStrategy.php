<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarVerisignGrs;

/**
 * Detects Verisign GRS format
 * 
 * Characteristics:
 * - "No match for domain" or similar Verisign patterns
 * - Verisign-specific headers
 * - .com/.net standard format
 */
class VerisignGrsDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 40;
    protected const NAME = 'Verisign GRS';
    
    // Very specific Verisign WHOIS header (not just brand mentions)
    protected const INDICATORS = [
        'The Registry database contains ONLY .COM, .NET',
        'Verisign Global Registry Services',
        'whois.verisign-grs.com',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Removed generic "Verisign"/"VERISIGN" - too broad
        // The first indicator is very specific and sufficient
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistrarVerisignGrs::class;
        }
        
        return null;
    }
}

