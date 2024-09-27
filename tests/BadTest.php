<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class BadTest extends TestCase
{

    /** @dataProvider BadData */
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
