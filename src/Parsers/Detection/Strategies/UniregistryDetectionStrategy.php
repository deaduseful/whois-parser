<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarUniregistry;

/**
 * Detects Uniregistry format
 * 
 * Characteristics:
 * - "is available for registration" message
 * - Uniregistry-specific patterns
 */
class UniregistryDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 60;
    protected const NAME = 'Uniregistry';
    
    protected const AVAILABLE_MESSAGE_INDICATORS = [
        ' is available for registration',
        'To register this domain',
    ];
    
    protected const BRAND_INDICATORS = [
        'uniregistry',
        'Uniregistry',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Check for Uniregistry specific available message (very distinctive)
        if ($this->containsAllIndicators($lookup, self::AVAILABLE_MESSAGE_INDICATORS)) {
            return RegistrarUniregistry::class;
        }
        
        // REMOVED: Generic brand mention check
        // "uniregistry" could appear in registrar name or mentions
        // The available message combination is distinctive enough
        
        return null;
    }
}

