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

        // Try to detect format from content before falling back to Generic
        if (empty($class)) {
            $class = FormatDetector::detect($lookup);
        }

        if (empty($class)) {
            $class = self::getDefaultParser();
        }

        return new $class($lookup, $whoisServer);
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
