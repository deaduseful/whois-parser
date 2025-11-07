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

    public function detect(string $lookup): ?string
    {
        if ($this->containsIndicators($lookup, self::PRIMARY_INDICATORS, 1)) {
            return RegistrarCentralNic::class;
        }
        return null;
    }
}

