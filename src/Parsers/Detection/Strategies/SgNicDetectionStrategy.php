<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\SgNic;

/**
 * Detects SGNIC format (Singapore Network Information Centre)
 * 
 * Characteristics:
 * - "SGNIC WHOIS Server" header
 * - Error codes like "Error code: 01044", "Error code: 02304"
 * - Singapore .sg TLD and related
 */
class SgNicDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 35; // After CentralNic, before Verisign
    protected const NAME = 'SGNIC (Singapore)';
    
    protected const INDICATORS = [
        'SGNIC WHOIS Server',
    ];
    
    public function detect(string $lookup): ?string
    {
        if ($this->containsIndicators($lookup, self::INDICATORS, 1)) {
            return SgNic::class;
        }
        
        return null;
    }
}

