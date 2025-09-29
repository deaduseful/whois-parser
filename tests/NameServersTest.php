<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;


class NameServersTest extends TestCase
{
    public function testNameServers()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $output = $parser->get();
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
