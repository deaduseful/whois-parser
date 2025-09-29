<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class BadTest extends TestCase
{
    /**
     * @dataProvider BadData
     */
    public function testInvalid(string $domain, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/bad/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $output = $parser->get();
        self::assertNull($output);
        $expected = 'Bad response';
        self::assertEquals($expected, $parser->getError(), $domain);
    }

    public function BadData(): array
    {
        return [
            ['nic.cm'],
        ];
    }
}
