<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;
use Deaduseful\WhoisParser\Parsers\Traits\CommonNotFoundPatterns;
use Deaduseful\WhoisParser\Parsers\Traits\ReservedByLookupContains;

/**
 * Generic WHOIS parser - fallback for unknown formats
 * 
 * This parser uses common patterns from WhoisPatterns to detect not found/reserved domains.
 * It serves as the default parser when no specific registry format is detected.
 */
class Generic extends KeyValue
{
    use CommonNotFoundPatterns;
    use ReservedByLookupContains;

    /**
     * @var string[] Common "not found" patterns across registries
     */
    protected array $matchNotFound = WhoisPatterns::NOT_FOUND;

    /**
     * @var string[] Reserved patterns
     */
    protected array $matchReserved = WhoisPatterns::RESERVED;

    /**
     * @var string[] Rate limit patterns
     */
    protected array $matchRateLimit = WhoisPatterns::RATE_LIMIT;

    public function getCreated(array $rawData): ?Carbon
    {
        return $rawData[self::DATE_CREATED] ?? null;
    }

    public function getUpdated(array $rawData): ?Carbon
    {
        return $rawData[self::DATE_UPDATED] ?? null;
    }

    public function getExpiry(array $rawData): ?Carbon
    {
        return $rawData[self::DATE_EXPIRY] ?? null;
    }
}
