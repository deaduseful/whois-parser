<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class NameServersTest extends TestCase
{
    public function testNameServers()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $output = $parser->getOutput();
        $actual = $output->name_servers;
        $expected = [
            'dns1.p04.nsone.net',
            'dns2.p04.nsone.net',
            'dns3.p04.nsone.net',
            'dns4.p04.nsone.net',
        ];
        $this->assertEquals($expected, $actual);
    }
}
