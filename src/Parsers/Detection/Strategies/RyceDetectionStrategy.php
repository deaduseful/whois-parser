<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarRyce;

/**
 * Detects Ryce format (.cologne, .koeln, .tirol, .wien)
 * 
 * Characteristics:
 * - German city TLDs
 * - NetCologne/Ryce specific patterns
 */
class RyceDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 120;
    protected const NAME = 'Ryce (German City TLDs)';
    
    protected const INDICATORS = [
        'NetCologne',
        'Ryce',
    ];
    
    public function detect(string $lookup): ?string
    {
        if ($this->containsIndicators($lookup, self::INDICATORS, 1)) {
            return RegistrarRyce::class;
        }
        
        return null;
    }
}

