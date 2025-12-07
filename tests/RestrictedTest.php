<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class RestrictedTest extends TestCase
{
    /**
     * @dataProvider restrictedData
     */
    public function testRestricted(string $domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/restricted/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain);
        $output = $parser->get();
        self::assertNull($output, $domain);
        self::assertTrue($parser->isReserved(), $domain);
    }

    public function restrictedData(): array
    {
        $glob = glob(__DIR__ . '/data/restricted/*.txt');
        return array_map(function ($value) {
            return [str_replace('.txt', '', basename($value))];
        }, $glob);
    }
}
