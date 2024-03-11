<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class NotFoundTest extends TestCase
{
    /** @dataProvider NotFoundData */
    public function testNotFound(string $domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/notfound/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain);
        $output = $parser->getOutput();
        self::assertNull($output);
        $expected = 'Not found';
        self::assertEquals($expected, $parser->getError(), $domain);
    }

    public function NotFoundData(): array
    {
        $glob = glob(__DIR__ . '/data/notfound/*.txt');
        return array_map(function ($value) {
           return [str_replace('.txt', '', basename($value))];
        }, $glob);
    }
}
