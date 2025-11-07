<?php

namespace Deaduseful\WhoisParser\Parsers\Detection\Strategies;

use Deaduseful\WhoisParser\Parsers\Detection\AbstractDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\RegistrarAfNic;

/**
 * Detects AFNIC registry format (.fr, .re, .tf, .wf, .yt, .pm)
 *
 * Characteristics:
 * - Whois banner starting with "This is the AFNIC Whois server"
 */
class AfnicDetectionStrategy extends AbstractDetectionStrategy
{
    protected int $priority = 45;
    protected const NAME = 'AFNIC';

    protected const INDICATORS = [
        'This is the AFNIC Whois server',
    ];

    public function detect(string $lookup): ?string
    {
        if ($this->containsIndicators($lookup, self::INDICATORS, 1, false)) {
            return RegistrarAfNic::class;
        }

        return null;
    }
}


