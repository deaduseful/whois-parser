<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\Parser;
use Tests\TestCase;

class NotFoundTest extends TestCase
{
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
            ['regenerate.co.in'],
        ];
    }
}
