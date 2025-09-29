<?php

namespace DeadusefulWhoisParser;

/**
 * Version management for the WhoisParser library
 * 
 * This class manages the overall library version and provides
 * a mechanism for version-based change detection in hashes.
 */
class Version
{
    /**
     * The current version of the WhoisParser
     * This should be incremented when:
     * - Major parsing logic changes
     * - Hash generation algorithm changes
     * - Breaking changes to the API
     */
    public const VERSION = '1.0.0';

    /**
     * Get the current version
     * 
     * @return string The version
     */
    public static function getVersion(): string
    {
        return self::VERSION;
    }

}
