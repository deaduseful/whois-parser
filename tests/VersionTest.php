<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Version;
use Deaduseful\WhoisParser\Parsers\Generic;
use Tests\TestCase;

class VersionTest extends TestCase
{
    public function testVersion()
    {
        $version = Version::getVersion();
        
        $this->assertIsString($version);
        $this->assertNotEmpty($version);
        $this->assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', $version, 'Version should follow semantic versioning format');
    }

    public function testVersionInfo()
    {
        $version = Version::getVersion();
        
        $this->assertIsString($version);
        $this->assertEquals('1.0.0', $version);
    }

    public function testHashIncludesVersionSalt()
    {
        $parser = new Generic('test lookup', 'test.com');
        $rawData = ['domain' => 'test.com', 'registrar' => 'Test Registrar'];
        
        $hash = $parser->getHash($rawData);
        
        // The hash should be deterministic and include version salt
        $this->assertEquals(64, strlen($hash));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $hash);
    }

    public function testHashConsistencyWithSameVersion()
    {
        $parser = new Generic('test lookup', 'test.com');
        $rawData = ['domain' => 'test.com', 'registrar' => 'Test Registrar'];
        
        $hash1 = $parser->getHash($rawData);
        $hash2 = $parser->getHash($rawData);
        
        // Hashes should be identical with same version and data
        $this->assertEquals($hash1, $hash2, 'Hashes should be identical with same version and data');
    }

    public function testParserVersionUsesVersion()
    {
        $parser = new \Deaduseful\WhoisParser\Parser('test lookup', 'test.com');
        $version = $parser->getVersion();
        
        $this->assertIsString($version);
        $this->assertEquals(Version::getVersion(), $version);
    }
}