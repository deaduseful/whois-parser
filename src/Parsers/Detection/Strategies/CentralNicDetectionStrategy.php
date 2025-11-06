<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarCentralNic;

/**
 * Detects CentralNic format
 * 
 * Characteristics:
 * - "The queried object does not exist"
 * - "Name Collision Reservation From ICANN"
 * - CentralNic-specific field formatting
 */
class CentralNicDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 30;
    protected const NAME = 'CentralNic';
    
    protected const PRIMARY_INDICATORS = [
        'The queried object does not exist',
        'Name Collision Reservation From ICANN',
    ];
    
    protected const SECONDARY_INDICATORS = [
        '.centralnic.',
        'CentralNic',
    ];
    
    public function detect(string $lookup): ?string
    {
        // PRIMARY_INDICATORS are very specific - these are CentralNic error messages
        if ($this->containsIndicators($lookup, self::PRIMARY_INDICATORS, 1)) {
            return RegistrarCentralNic::class;
        }
        
        // REMOVED: SECONDARY_INDICATORS check
        // ".centralnic." and "CentralNic" could appear in nameservers or mentions
        // Only use the highly specific error message patterns
        
        return null;
    }
}

