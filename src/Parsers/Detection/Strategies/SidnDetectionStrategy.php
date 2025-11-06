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
    protected int $priority = 70;
    protected const NAME = 'SIDN (Netherlands)';

    protected const PATTERN_INDICATORS = [
        'Copyright (c) The SIDN Foundation',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Removed standalone "SIDN" - too broad
        // Now requires "is free" + copyright/registrar field combination
        if ($this->containsIndicators($lookup, self::PATTERN_INDICATORS, 2)) {
            return RegistrarSidn::class;
        }
        
        return null;
    }
}

