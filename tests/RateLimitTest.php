<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class RateLimitTest extends TestCase
{
    /**
     * @dataProvider RateLimitedData
     */
    public function testRateLimited(string $domain, ?string $whoisServer = null)
    {
        $filename = __DIR__ . '/data/ratelimit/' . $domain . '.txt';
        $lookup = file_get_contents($filename);
        $parser = new Parser($lookup, $domain, $whoisServer);
        $output = $parser->get();
        self::assertNull($output);
        $expected = 'Rate limited';
        self::assertEquals($expected, $parser->getError());
    }

    public function RateLimitedData(): array
    {
        return [
            ['devonhampers.com', 'whois.joker.com'],
            ['glucofence.org', null],
            ['hebrewbooks.org', null],
            ['nic.lincoln', 'whois.nic.lincoln'],
            ['qcmf.com.au', null, 'whois.auda.org.au'],
            ['test.ch', null],
            ['test.com', 'whois.networksolutions.com'],
            ['test.cz', null],
            ['test.vip', null],
            ['tubosparis.es', null],
            ['whois.dynadot.com', 'whois.dynadot.com'],
            ['whois.nominet.uk', null],
        ];
    }
}
