<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class ReservedTest extends TestCase
{
    /**
     * @dataProvider DomainData
     */
    public function testReserved(string $domain, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/reserved/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $output = $parser->get();
        self::assertNull($output);
        $expected = 'Reserved';
        self::assertEquals($expected, $parser->getError(), $domain);
    }

    public function DomainData(): array
    {
        return [
            ['agogo.tv'],
            ['story.io'],
            ['example.org.my'],
            ['test.top'],
        ];
    }
}
