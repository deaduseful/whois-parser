<?php

declare(strict_types=1);

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class MultipleContactsTest extends TestCase
{
    public function testMultipleAdminContacts()
    {
        // Test using timortelecom.tl which has multiple admin contacts
        $query = 'timortelecom.tl';
        $lookup = file_get_contents(__DIR__ . '/data/taken/' . $query . '.txt');

        $parser = new Parser($lookup, $query);
        $response = $parser->get();
        $actual = json_decode((string)$response);

        // Make sure the response isn't null
        $this->assertNotNull($response);

        // Test that admin contacts are correctly parsed as an array
        $this->assertIsArray($actual->whois->contacts->admin);
        $this->assertCount(2, $actual->whois->contacts->admin);

        // Test the first admin contact
        $this->assertEquals('Timor Telecom', $actual->whois->contacts->admin[0]->name);
        $this->assertEquals('Timor Telecom, SA', $actual->whois->contacts->admin[0]->org);
        $this->assertEquals('info@timortelecom.tl', $actual->whois->contacts->admin[0]->email);

        // Test the second admin contact
        $this->assertEquals('Timor Telecom DEO/OSI', $actual->whois->contacts->admin[1]->name);
        $this->assertEquals('Timor Telecom, SA', $actual->whois->contacts->admin[1]->org);
        $this->assertEquals('osi@timortelecom.tl', $actual->whois->contacts->admin[1]->email);

        // Make sure registrant and other contacts are still properly parsed as single contacts
        $this->assertIsObject($actual->whois->contacts->registrant);
        $this->assertEquals('Timor Telecom', $actual->whois->contacts->registrant->name);

        $this->assertIsObject($actual->whois->contacts->tech);
        $this->assertEquals('Timor Telecom DEO/OSI', $actual->whois->contacts->tech->name);

        $this->assertIsObject($actual->whois->contacts->billing);
        $this->assertEquals('Jose Dias', $actual->whois->contacts->billing->name);
    }

    public function testSingleContactsAreNotArrays()
    {
        // Use a different domain that doesn't have multiple contacts to ensure we keep
        // backward compatibility with the existing format
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');

        $parser = new Parser($lookup, $query);
        $response = $parser->get();
        $actual = json_decode((string)$response);

        // Make sure the response isn't null
        $this->assertNotNull($response);

        // Check that single contacts are still objects, not arrays
        $this->assertIsObject($actual->whois->contacts->registrant);
        $this->assertIsObject($actual->whois->contacts->admin);
        $this->assertIsObject($actual->whois->contacts->tech);
    }
}
