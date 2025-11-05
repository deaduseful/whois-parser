<?php

namespace Deaduseful\WhoisParser\Parsers;

class Factory
{
    private static ?array $config = null;

    /**
     * Load configuration from config files
     */
    private static function loadConfig(): array
    {
        if (self::$config === null) {
            $configPath = __DIR__ . '/../../config.php';
            if (file_exists($configPath)) {
                self::$config = require $configPath;
            } else {
                self::$config = [];
            }
        }
        return self::$config;
    }

    /**
     * Get WHOIS server mappings
     */
    private static function getWhoisServerMappings(): array
    {
        $config = self::loadConfig();
        return $config['whois_servers'] ?? [];
    }

    /**
     * Get TLD mappings
     */
    private static function getTldMappings(): array
    {
        $config = self::loadConfig();
        return $config['tlds'] ?? [];
    }

    /**
     * Get default parser class
     */
    private static function getDefaultParser(): string
    {
        $config = self::loadConfig();
        return $config['default_parser'] ?? Generic::class;
    }

    /**
     * Get parser configurations
     */
    private static function getParsers(): array
    {
        $config = self::loadConfig();
        return $config['parsers'] ?? [];
    }

    /**
     * Create a parser instance, using ConfigurableParser if config exists
     */
    private static function createParserInstance(string $classOrName, string $lookup, ?string $whoisServer): Strategy
    {
        $parsers = self::getParsers();
        
        // If the string doesn't contain a backslash, it's a config-based parser name
        if (strpos($classOrName, '\\') === false) {
            // Check if this parser has a configuration
            if (isset($parsers[$classOrName])) {
                // Use ConfigurableParser with the configuration
                return new ConfigurableParser($lookup, $whoisServer, $parsers[$classOrName]);
            }
            // If not in config, build the full class name and try to instantiate
            $class = 'Deaduseful\\WhoisParser\\Parsers\\' . $classOrName;
            return new $class($lookup, $whoisServer);
        }
        
        // If it contains a backslash, it's a fully-qualified class name
        // Extract just the class name to check if it has a config override
        $className = substr($classOrName, strrpos($classOrName, '\\') + 1);
        if (isset($parsers[$className])) {
            // Use ConfigurableParser with the configuration
            return new ConfigurableParser($lookup, $whoisServer, $parsers[$className]);
        }
        
        // Otherwise, instantiate the class directly
        return new $classOrName($lookup, $whoisServer);
    }

    public static function create(string $lookup, string $query = null, string $whoisServer = null): Strategy
    {
        if (empty($whoisServer)) {
            $whoisServer = self::parseWhoisServer($lookup);
        }

        $class = null;
        if ($whoisServer) {
            $class = self::getClassByWhoisServer($whoisServer);
        }

        if (empty($class)) {
            $class = self::getClass($query);
        }

        if (empty($class)) {
            $class = self::getDefaultParser();
        }

        return self::createParserInstance($class, $lookup, $whoisServer);
    }

    protected static function parseTld(string $query): ?string
    {
        if (strpos($query, '.')) {
            return pathinfo(strtolower($query), PATHINFO_EXTENSION);
        }
        return null;
    }

    protected static function getClassByWhoisServer(string $whoisServer): ?string
    {
        $whoisServer = strtolower($whoisServer);
        $mappings = self::getWhoisServerMappings();

        if (isset($mappings[$whoisServer])) {
            return $mappings[$whoisServer];
        }
        return null;
    }

    protected static function parseWhoisServer(string $lookup): ?string
    {
        $pattern = '/Registrar WHOIS Server:\s*(\S+)/i';
        if (preg_match($pattern, $lookup, $matches)) {
            return $matches[1];
        }
        return null;
    }

    protected static function getClass(?string $query): ?string
    {
        if (strpos($query, '.')) {
            $tld = self::parseTld($query);
            $mappings = self::getTldMappings();

            if (isset($mappings[$tld])) {
                return $mappings[$tld];
            }
        }
        return null;
    }
}
