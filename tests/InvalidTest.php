<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class InvalidTest extends TestCase
{

    /** @dataProvider InvalidData */
    public function testInvalid(string $domain, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/invalid/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $output = $parser->get();
        self::assertNull($output);
        $expected = 'Invalid query';
        self::assertEquals($expected, $parser->getError(), $domain);
    }

    public function InvalidData(): array
    {
        return [
            ['7.eu'],
            ['lookup-----eg--example-com-or-192-168-0-1-.co.nz'],
            ['invalid-tld-c7fa0926.local', 'whois.nic.uk'],
            ['nic.ke'],
        ];
    }
}
