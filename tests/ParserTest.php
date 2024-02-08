<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\Parser;
use Tests\TestCase;
class ParserTest extends TestCase
{
    public function testParser()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $json = file_get_contents(__DIR__ . '/data/' . $query . '.json');
        $expected = json_decode($json);
        $parser = new Parser($lookup, '2023-04-14');
        $actual = $parser->getData();
        $this->assertEquals($expected, $actual);
    }

    /** @dataProvider NotFoundData */
    public function testNotFound($domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup);
        $actual = $parser->getData();
        $expected = json_decode('{ "error": "Domain not found" }');
        self::assertEquals($expected, $actual);
    }

    public function NotFoundData(): array
    {
        return [
            ['07.tv'],
            ['walksolutions.org'],
            ['cambridge.org'],
            ['dns-001.com'],
            ['smock.co.nz'],
        ];
    }

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
        ];
    }
}
