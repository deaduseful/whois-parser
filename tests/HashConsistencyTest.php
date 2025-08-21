<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parsers\Generic;
use Tests\TestCase;

class HashConsistencyTest extends TestCase
{
    /**
     * Test that the same WHOIS data always produces the exact same hash
     * This is critical for the change detection functionality
     */
    public function testHashIsDeterministic()
    {
        $testDomains = [
            'domaintools.com',
            'google.co.il',
            'zara',
            'sausage.io',
            'giveaway.gg'
        ];
        
        foreach ($testDomains as $domain) {
            $this->testHashDeterminismForDomain($domain);
        }
    }
    
    private function testHashDeterminismForDomain(string $domain): void
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Generic($lookup);
        $rawData = $parser->getParsed();
        
        // Generate hash 10 times to ensure consistency
        $hashes = [];
        for ($i = 0; $i < 10; $i++) {
            $hashes[] = $parser->getHash($rawData);
        }
        
        // All hashes should be exactly the same
        $firstHash = $hashes[0];
        foreach ($hashes as $index => $hash) {
            $this->assertEquals(
                $firstHash, 
                $hash, 
                "Hash for {$domain} should be consistent (iteration {$index})"
            );
        }
        
        // Verify hash format
        $this->assertIsString($firstHash, "Hash should be a string for {$domain}");
        $this->assertEquals(64, strlen($firstHash), "Hash should be 64 characters for {$domain}");
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $firstHash, "Hash should be valid SHA256 hex for {$domain}");
        
        echo "✅ {$domain}: Hash is deterministic (" . substr($firstHash, 0, 8) . "...)\n";
    }
    
    /**
     * Test that different WHOIS data produces different hashes
     */
    public function testHashUniqueness()
    {
        $testDomains = [
            'domaintools.com',
            'google.co.il',
            'zara',
            'sausage.io',
            'giveaway.gg'
        ];
        
        $hashes = [];
        foreach ($testDomains as $domain) {
            $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
            $parser = new Generic($lookup);
            $rawData = $parser->getParsed();
            $hashes[$domain] = $parser->getHash($rawData);
        }
        
        // All hashes should be unique
        $uniqueHashes = array_unique($hashes);
        $this->assertEquals(
            count($hashes), 
            count($uniqueHashes), 
            "All domains should have unique hashes"
        );
        
        echo "✅ All domains have unique hashes\n";
        
        // Show the hashes for verification
        foreach ($hashes as $domain => $hash) {
            echo "  {$domain}: " . substr($hash, 0, 8) . "...\n";
        }
    }
    
    /**
     * Test that the hash method handles edge cases gracefully
     */
    public function testHashEdgeCases()
    {
        // Test with empty data
        $parser = new Generic('');
        $rawData = $parser->getParsed();
        $emptyHash = $parser->getHash($rawData);
        
        $this->assertIsString($emptyHash, "Empty data should produce a string hash");
        $this->assertEquals(64, strlen($emptyHash), "Empty data hash should be 64 characters");
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $emptyHash, "Empty data hash should be valid SHA256");
        
        // Test with minimal data
        $minimalParser = new Generic('Domain: test.com');
        $minimalRawData = $minimalParser->getParsed();
        $minimalHash = $minimalParser->getHash($minimalRawData);
        
        $this->assertIsString($minimalHash, "Minimal data should produce a string hash");
        $this->assertEquals(64, strlen($minimalHash), "Minimal data hash should be 64 characters");
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $minimalHash, "Minimal data hash should be valid SHA256");
        
        echo "✅ Edge cases handled correctly\n";
    }
}
