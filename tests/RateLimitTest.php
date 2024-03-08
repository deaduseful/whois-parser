<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class RateLimitTest extends TestCase
{

    /** @dataProvider RateLimitedData */
    public function testRateLimited(string $domain, ?string $whoisServer)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $output = $parser->getOutput();
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
            ['qcmf.com.au', null],
            ['test.vip', null],
            ['test.cz', null],
        ];
    }
}
