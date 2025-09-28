<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parsers\Generic;
use Tests\TestCase;

class HashTest extends TestCase
{
    /** @dataProvider hashData */
    public function testHashMethodGeneratesConsistentHash(string $domain, ?string $whoisServer = null)
    {
        $filePath = __DIR__ . '/data/' . $domain . '.txt';
        if (!file_exists($filePath)) {
            $this->markTestSkipped("Test data file not found: {$filePath}");
        }
        $lookup = file_get_contents($filePath);
        $parser = new Generic($lookup, $whoisServer);

        // Parse the data first
        $rawData = $parser->getParsed();

        // Get the hash multiple times to ensure consistency
        $hash1 = $parser->getHash($rawData);
        $hash2 = $parser->getHash($rawData);
        $hash3 = $parser->getHash($rawData);

        // All hashes should be exactly the same
        $this->assertEquals($hash1, $hash2, "Hash should be consistent for {$domain} (1st vs 2nd)");
        $this->assertEquals($hash2, $hash3, "Hash should be consistent for {$domain} (2nd vs 3rd)");
        $this->assertEquals($hash1, $hash3, "Hash should be consistent for {$domain} (1st vs 3rd)");

        // Hash should be a valid SHA256 string
        $this->assertIsString($hash1, "Hash should be a string for {$domain}");
        $this->assertEquals(64, strlen($hash1), "Hash should be 64 characters for {$domain}");
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $hash1, "Hash should be valid SHA256 hex for {$domain}");

        // Hash should not be null
        $this->assertNotNull($hash1, "Hash should not be null for {$domain}");
    }

    /** @dataProvider hashData */
    public function testHashMethodExcludesTimestamps(string $domain, ?string $whoisServer = null)
    {
        $filePath = __DIR__ . '/data/' . $domain . '.txt';
        if (!file_exists($filePath)) {
            $this->markTestSkipped("Test data file not found: {$filePath}");
        }
        $lookup = file_get_contents($filePath);
        $parser = new Generic($lookup, $whoisServer);

        // Parse the data first
        $rawData = $parser->getParsed();

        // Get the hash from the original data
        $originalHash = $parser->getHash($rawData);

        // Create modified data with different timestamps
        $modifiedRawData = $rawData;
        if (isset($modifiedRawData['updated'])) {
            $modifiedRawData['updated'] = '2021-01-09T23:06:29.00Z'; // Different timestamp
        }
        if (isset($modifiedRawData['created'])) {
            $modifiedRawData['created'] = '1999-08-02T04:00:00.00Z'; // Different timestamp
        }
        if (isset($modifiedRawData['expiry'])) {
            $modifiedRawData['expiry'] = '2028-08-01T04:00:00.00Z'; // Different timestamp
        }

        $modifiedHash = $parser->getHash($modifiedRawData);

        // Hash should be the same since we exclude timestamps
        $this->assertEquals($originalHash, $modifiedHash, "Hash should be the same for {$domain} even with different timestamps");
    }

    /** @dataProvider hashData */
    public function testHashMethodDetectsContentChanges(string $domain, ?string $whoisServer = null)
    {
        $filePath = __DIR__ . '/data/' . $domain . '.txt';
        if (!file_exists($filePath)) {
            $this->markTestSkipped("Test data file not found: {$filePath}");
        }
        $lookup = file_get_contents($filePath);
        $parser = new Generic($lookup, $whoisServer);

        // Parse the data first
        $rawData = $parser->getParsed();

        // Get the hash from the original data
        $originalHash = $parser->getHash($rawData);

        // Create modified data with different content - try multiple fields
        $modifiedRawData = $rawData;
        $fieldModified = false;
        
        // Try to modify a field that exists and is used in hash calculation
        // Based on the hash generation logic, these are the fields that matter:
        $fieldsToTry = ['registrar', 'domainName', 'domain', 'registryDomainID', 'dnssec', 'status'];
        $modifiedField = null;
        foreach ($fieldsToTry as $field) {
            if (isset($modifiedRawData[$field])) {
                $modifiedRawData[$field] = 'DIFFERENT_' . $field . '_VALUE';
                $fieldModified = true;
                $modifiedField = $field;
                break;
            }
        }
        
        // If no common fields exist, modify the first available field
        if (!$fieldModified && !empty($rawData)) {
            $firstKey = array_keys($rawData)[0];
            if (!is_array($rawData[$firstKey])) { // Don't modify arrays
                $modifiedRawData[$firstKey] = 'DIFFERENT_VALUE';
                $fieldModified = true;
                $modifiedField = $firstKey;
            }
        }

        // If we still couldn't modify anything, skip this test
        if (!$fieldModified) {
            $this->markTestSkipped("No suitable fields found to modify for {$domain}. Available fields: " . implode(', ', array_keys($rawData)));
        }

        $modifiedHash = $parser->getHash($modifiedRawData);

        // Hash should be different for different content
        $this->assertNotEquals($originalHash, $modifiedHash, "Hash should be different for {$domain} when content changes. Original: {$originalHash}, Modified: {$modifiedHash}. Field modified: {$modifiedField}. Raw data keys: " . implode(', ', array_keys($rawData)));
    }

    /** @dataProvider hashData */
    public function testHashMethodHandlesRealWhoisData(string $domain, ?string $whoisServer = null)
    {
        $filePath = __DIR__ . '/data/' . $domain . '.txt';
        if (!file_exists($filePath)) {
            $this->markTestSkipped("Test data file not found: {$filePath}");
        }
        $lookup = file_get_contents($filePath);
        $parser = new Generic($lookup, $whoisServer);

        // Parse the data first
        $rawData = $parser->getParsed();

        $hash = $parser->getHash($rawData);

        // Basic validation
        $this->assertIsString($hash, "Hash should be a string for {$domain}");
        $this->assertEquals(64, strlen($hash), "Hash should be 64 characters for {$domain}");
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $hash, "Hash should be valid SHA256 hex for {$domain}");
        $this->assertNotNull($hash, "Hash should not be null for {$domain}");

        // Hash should be consistent across multiple calls
        $hash2 = $parser->getHash($rawData);
        $this->assertEquals($hash, $hash2, "Hash should be consistent for {$domain} across multiple calls");
    }

    public function testHashMethodHandlesEmptyData()
    {
        $parser = new Generic('', 'empty.com');
        $rawData = $parser->getParsed();
        $hash = $parser->getHash($rawData);

        // Even with empty data, we should get a valid hash
        $this->assertIsString($hash);
        $this->assertEquals(64, strlen($hash));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $hash);
    }

    public function testHashMethodHandlesErrorCases()
    {
        // Test with invalid data that might cause errors
        $parser = new Generic('invalid data', 'invalid.com');
        $rawData = $parser->getParsed();
        $hash = $parser->getHash($rawData);

        // Should still return a valid hash or null, but not crash
        $this->assertIsString($hash);
        $this->assertEquals(64, strlen($hash));
        $this->assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $hash);
    }

    public function hashData(): array
    {
        return [
            // Standard .com domain
            ['domaintools.com'],

            // Country code domains
            ['google.co.il'],
            ['google.co.ug'],
            ['google.pt'],
            ['test.co.uk'],
            ['test.fi'],
            ['test.ro'],
            ['test.ax'],
            ['test.je'],

            // Various TLDs
            ['zara'],
            ['sausage.io'],
            ['giveaway.gg'],
            ['test.online'],
            ['woodchuck.app'],
            ['intercede.app'],
            ['covid-19.xyz'],
            ['3xxx.info'],
            ['alpha-tonic.info'],

            // Different registrars
            ['clickbank.net'],
            ['salsalessonschicago.org'],
            ['spb24.net'],
            ['papatyedernegi.org'],

            // Various statuses and configurations
            ['bank.gov.ua'],
            ['kmu.gov.ua'],
            ['nma.com'],
            ['aname.net'],
            ['cambridge.org'],
            ['walksolutions.org'],
            ['www.gov.bn'],

            // Edge cases
            ['ess.do'],
            ['n.pl'],
            ['q.gs'],
            ['mana.md'],
            ['xexample.ru'],
        ];
    }
}
