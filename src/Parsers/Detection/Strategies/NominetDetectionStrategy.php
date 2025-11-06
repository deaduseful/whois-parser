<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarNominet;

/**
 * Detects Nominet format (UK registry)
 * 
 * Characteristics:
 * - "Registrar:" field
 * - "Registered on:" or "Expiry date:" fields
 * - "Registration status:" field
 * - UK-style formatting with sections
 */
class NominetDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 10;
    protected const NAME = 'Nominet (UK Registry)';
    
    protected const INDICATORS = [
        'Registered on:',
        'Registration status:',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Need at least 2 indicators for confident detection
        if ($this->containsIndicators($lookup, self::INDICATORS, 2)) {
            return RegistrarNominet::class;
        }
        
        return null;
    }
}

