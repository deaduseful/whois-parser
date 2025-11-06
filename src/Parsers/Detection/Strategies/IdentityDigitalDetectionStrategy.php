<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarIdentityDigital;

/**
 * Detects Identity Digital format (formerly Donuts/Afilias)
 * 
 * Characteristics:
 * - "Registry Domain ID:"
 * - "Registrar WHOIS Server:"
 * - "Registry Expiry Date:"
 * - Thick registry format with ICANN standard fields
 */
class IdentityDigitalDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 20;
    protected const NAME = 'Identity Digital (Donuts/Afilias)';
    
    protected const PRIMARY_INDICATORS = [
        'Registry Domain ID:',
        'Registrar WHOIS Server:',
        'Registry Expiry Date:',
    ];
    
    protected const NOT_FOUND_INDICATORS = [
        'Domain not found.',
        'Last update of WHOIS database:',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Need at least 2 indicators for confident detection
        if ($this->containsIndicators($lookup, self::PRIMARY_INDICATORS, 2)) {
            return RegistrarIdentityDigital::class;
        }
        
        // Also check for Identity Digital specific not found pattern
        if ($this->containsAllIndicators($lookup, self::NOT_FOUND_INDICATORS)) {
            return RegistrarIdentityDigital::class;
        }
        
        return null;
    }
}

