<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class ReservedTest extends TestCase
{
    /**
     * @dataProvider reservedData
     */
    public function testReserved(string $domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/reserved/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain);
        $output = $parser->get();
        self::assertNull($output, $domain);
        $expected = 'Reserved';
        self::assertEquals($expected, $parser->getError(), $domain);
    }

    public function reservedData(): array
    {
        $glob = glob(__DIR__ . '/data/reserved/*.txt');
        return array_map(function ($value) {
            return [str_replace('.txt', '', basename($value))];
        }, $glob);
    }
}
