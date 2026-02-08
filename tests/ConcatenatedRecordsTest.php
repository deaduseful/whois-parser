<?php

declare(strict_types=1);

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\ContactParser;
use Deaduseful\WhoisParser\Parser;

/**
 * Tests for concatenated WHOIS records (two responses in one body).
 * Ensures we either parse only the block for the queried domain, or assign
 * scalar values so Contact ?string properties never receive an array (no TypeError).
 */
class ConcatenatedRecordsTest extends TestCase
{
    /**
     * When the WHOIS body contains two "Domain Name:" blocks, the parser should
     * return the single record for the queried domain (24x7ads.com), not throw
     * and not merge both blocks into array-valued contact fields.
     */
    public function testParsesOnlyBlockMatchingQueriedDomain(): void
    {
        $path = __DIR__ . '/data/concatenated/24x7ads.com.txt';
        $this->assertFileExists($path);
        $lookup = file_get_contents($path);
        $queriedDomain = '24x7ads.com';
        $whoisServer = 'whois.verisign-grs.com';

        $parser = new Parser($lookup, $queriedDomain, $whoisServer);
        $response = $parser->get();

        $this->assertNotNull($response, 'Parser should return a response without throwing');
        $whois = $response->whois;
        $this->assertNotNull($whois);
        $this->assertSame('24x7ads.com', strtolower($whois->domain ?? ''), 'Domain should be the queried one (second block)');

        $registrant = $whois->contacts->registrant;
        $this->assertIsObject($registrant);
        $this->assertSame('24x7 Ads Org', $registrant->org, 'Registrant org should be from the second block');
        $this->assertSame('Second Domain Contact', $registrant->name);
        $this->assertSame('second@24x7ads.com', $registrant->email);

        $this->assertIsString($registrant->org);
        $this->assertIsString($registrant->name);
        $this->assertIsString($registrant->email);
    }

    /**
     * Defensive assignment: when raw data has array values (e.g. duplicate keys
     * from concatenated records without normalisation), ContactParser must
     * coerce to scalar so that Contact ?string properties never receive an array.
     */
    public function testDefensiveAssignmentWhenContactValueIsArray(): void
    {
        $rawData = [
            'registrantName' => 'Single Name',
            'registrantOrganization' => ['First Org', 'Second Org'],
            'registrantCity' => 'City',
            'registrantState' => 'State',
            'registrantPostalCode' => '12345',
            'registrantCountry' => 'US',
            'registrantPhone' => '+1.5550000000',
            'registrantFax' => null,
            'registrantEmail' => 'email@example.com',
            'registrantStreet' => 'Street 1',
        ];

        $contactParser = new ContactParser($rawData);
        $contact = $contactParser->getContact('registrant');

        $this->assertIsObject($contact);
        $this->assertIsString($contact->org, 'org must be scalar (first element), not array');
        $this->assertSame('First Org', $contact->org);
        $this->assertIsString($contact->name);
        $this->assertSame('Single Name', $contact->name);
    }
}
