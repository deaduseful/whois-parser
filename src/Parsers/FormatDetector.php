<?php

namespace Deaduseful\WhoisParser\Parsers;

use Deaduseful\WhoisParser\Parsers\Detection\DetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\NominetDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\TcinetRuDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\IdentityDigitalDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\CentralNicDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\SgNicDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\VerisignGrsDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\GodaddyDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\UniregistryDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\SidnDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\AfricaRegistryDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\IndiaRegistryDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\AmazonDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\ChannelIslandsDetectionStrategy;
use Deaduseful\WhoisParser\Parsers\Detection\Strategies\RyceDetectionStrategy;

/**
 * Detects WHOIS format by analyzing the content using registered strategies
 * 
 * This class coordinates multiple detection strategies and applies them in priority order
 * to identify the appropriate parser for a WHOIS response.
 */
class FormatDetector
{
    /**
     * @var DetectionStrategy[]
     */
    private static array $strategies = [];
    
    /**
     * @var bool
     */
    private static bool $initialized = false;
    
    /**
     * Detect the appropriate parser class based on WHOIS content
     * 
     * @param string $lookup The raw WHOIS response
     * @return string|null The parser class name or null if no detection
     */
    public static function detect(string $lookup): ?string
    {
        if (empty($lookup)) {
            return null;
        }
        
        self::initialize();
        
        // Check each strategy in priority order
        foreach (self::$strategies as $strategy) {
            $result = $strategy->detect($lookup);
            if ($result !== null) {
                return $result;
            }
        }
        
        return null;
    }
    
    /**
     * Register a new detection strategy
     * 
     * @param DetectionStrategy $strategy The strategy to register
     * @return void
     */
    public static function registerStrategy(DetectionStrategy $strategy): void
    {
        self::$strategies[] = $strategy;
        self::sortStrategies();
    }
    
    /**
     * Register multiple strategies at once
     * 
     * @param DetectionStrategy[] $strategies Array of strategies to register
     * @return void
     */
    public static function registerStrategies(array $strategies): void
    {
        foreach ($strategies as $strategy) {
            self::$strategies[] = $strategy;
        }
        self::sortStrategies();
    }
    
    /**
     * Get all registered strategies
     * 
     * @return DetectionStrategy[]
     */
    public static function getStrategies(): array
    {
        self::initialize();
        return self::$strategies;
    }
    
    /**
     * Clear all registered strategies
     * Useful for testing or custom configurations
     * 
     * @return void
     */
    public static function clearStrategies(): void
    {
        self::$strategies = [];
        self::$initialized = false;
    }
    
    /**
     * Initialize default strategies
     * 
     * @return void
     */
    private static function initialize(): void
    {
        if (self::$initialized) {
            return;
        }
        
        // Register all default strategies
        self::registerStrategies([
            new NominetDetectionStrategy(),
            new TcinetRuDetectionStrategy(),
            new IdentityDigitalDetectionStrategy(),
            new SgNicDetectionStrategy(),
            new VerisignGrsDetectionStrategy(),
            new GodaddyDetectionStrategy(),
            new SidnDetectionStrategy(),
            new AfricaRegistryDetectionStrategy(),
            new IndiaRegistryDetectionStrategy(),
            new AmazonDetectionStrategy(),
            new ChannelIslandsDetectionStrategy(),
            new RyceDetectionStrategy(),
        ]);
        
        self::$initialized = true;
    }
    
    /**
     * Sort strategies by priority (lower number = higher priority)
     * 
     * @return void
     */
    private static function sortStrategies(): void
    {
        usort(self::$strategies, function (DetectionStrategy $a, DetectionStrategy $b) {
            return $a->getPriority() <=> $b->getPriority();
        });
    }
}
