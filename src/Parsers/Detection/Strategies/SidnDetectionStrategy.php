<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarSidn;

/**
 * Detects SIDN format (Netherlands)
 * 
 * Characteristics:
 * - Dutch-language fields
 * - SIDN-specific formatting
 * - "is free" message
 */
class SidnDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 18;
    protected const NAME = 'SIDN (Netherlands)';

    protected const INDICATORS = [
        ' is free',
        'Record maintained by: SIDN',
        'SIDN BV',
    ];
    
    public function detect(string $lookup): ?string
    {
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistrarSidn::class;
        }
        
        return null;
    }
}

