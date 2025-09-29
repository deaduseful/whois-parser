<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;


class RateLimitTest extends TestCase
{

    /** @dataProvider RateLimitedData */
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
            ['tubosparis.es', null],
            ['devonhampers.com', 'whois.joker.com'],
            ['test.ch', null],
            ['test.com', 'whois.networksolutions.com'],
            ['dogtrainingcollar.org', 'whois.godaddy.com'],
            ['glucofence.org', null],
            ['hebrewbooks.org', null],
            ['qcmf.com.au', null, 'whois.auda.org.au'],
            ['test.vip', null],
            ['test.cz', null],
            ['whois.nominet.uk', null],
            ['whois.dynadot.com', 'whois.dynadot.com'],
        ];
    }
}
