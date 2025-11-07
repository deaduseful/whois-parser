<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarGodaddy;

/**
 * Detects GoDaddy format
 * 
 * Characteristics:
 * - GoDaddy-specific WHOIS server response format
 * - NOT just any mention of "GoDaddy" (could be registrar or nameservers)
 * - Must have GoDaddy WHOIS-specific patterns
 */
class GodaddyDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 17;
    protected const NAME = 'GoDaddy';
    
    // More specific indicators that identify GoDaddy WHOIS format
    // Not just any mention of GoDaddy (which could be registrar/nameservers)
    protected const INDICATORS = [
        'The data contained in GoDaddy.com',
        'whois.godaddy.com',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Require specific GoDaddy WHOIS server patterns
        // Not just any mention of "godaddy" which could be in nameservers, registrar name, etc.
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistrarGodaddy::class;
        }
        
        return null;
    }
}

