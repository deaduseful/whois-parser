<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarChannelIsles;

/**
 * Detects Channel Islands format (.gg, .je, .as)
 * 
 * Characteristics:
 * - Channel Islands specific patterns
 * - Island Biz Ltd formatting
 */
class ChannelIslandsDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 110;
    protected const NAME = 'Channel Islands';
    
    protected const INDICATORS = [
        'Island Networks',
    ];
    
    public function detect(string $lookup): ?string
    {
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistrarChannelIsles::class;
        }
        
        return null;
    }
}

