<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class InvalidTest extends TestCase
{

    /** @dataProvider InvalidData */
    public function testInvalid(string $domain, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $output = $parser->getOutput();
        self::assertNull($output);
        $expected = 'Invalid query';
        self::assertEquals($expected, $parser->getError());
    }

    public function InvalidData(): array
    {
        return [
            ['7.eu'],
            ['agogo.tv'],
            ['darknet.sb'],
            ['test.top'],
        ];
    }
}
