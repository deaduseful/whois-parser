<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class NotFoundTest extends TestCase
{
    /** @dataProvider NotFoundData */
    public function testNotFound(string $domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain);
        $output = $parser->getOutput();
        self::assertNull($output);
        $expected = 'Domain not found';
        self::assertEquals($expected, $parser->getError());
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
            ['xexample.ru'],
        ];
    }
}
