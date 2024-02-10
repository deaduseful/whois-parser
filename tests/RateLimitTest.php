<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\Parser;
use Tests\TestCase;
class RateLimitTest extends TestCase
{

    /** @dataProvider RateLimitedData */
    public function testRateLimited($domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup);
        $actual = $parser->getData();
        $expected = json_decode('{ "error": "Rate limited" }');
        self::assertEquals($expected, $actual);
    }

    public function RateLimitedData(): array
    {
        return [
            ['tubosparis.es'],
            ['devonhampers.com'],
            ['test.ch'],
            ['test.com'],
            ['dogtrainingcollar.org'],
            ['glucofence.org'],
            ['hebrewbooks.org'],
            ['qcmf.com.au'],
            ['test.vip'],
            ['test.cz'],
        ];
    }
}
