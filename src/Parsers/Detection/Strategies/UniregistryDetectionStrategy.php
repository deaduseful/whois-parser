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
        'https://uniregistry.link',
    ];

    protected const GENERAL_INDICATORS = [
        'The data in this record is provided by Uniregistry',
        'Registrar: Uniregistry',
        'Uniregistry reserves the right to modify these terms',
    ];
    
    public function detect(string $lookup): ?string
    {
        // Check for Uniregistry specific available message (very distinctive)
        if ($this->containsAllIndicators($lookup, self::AVAILABLE_MESSAGE_INDICATORS)) {
            return RegistrarUniregistry::class;
        }

        if ($this->containsIndicators($lookup, self::GENERAL_INDICATORS, 2, false)) {
            return RegistrarUniregistry::class;
        }

        return null;
    }
}

