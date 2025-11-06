<?php

namespace Deaduseful\WhoisParser\Parsers\Detection;

/**
 * Interface for WHOIS format detection strategies
 * 
 * Each strategy is responsible for detecting a specific WHOIS format
 * by analyzing the raw WHOIS response content.
 */
interface DetectionStrategy
{
    /**
     * Detect if this strategy matches the given WHOIS lookup
     * 
     * @param string $lookup The raw WHOIS response text
     * @return string|null The parser class name if detected, null otherwise
     */
    public function detect(string $lookup): ?string;
    
    /**
     * Get the priority of this strategy (lower number = higher priority)
     * Strategies are checked in priority order
     * 
     * @return int Priority value (default: 100)
     */
    public function getPriority(): int;
    
    /**
     * Get a human-readable name for this detection strategy
     * 
     * @return string Strategy name
     */
    public function getName(): string;
}

