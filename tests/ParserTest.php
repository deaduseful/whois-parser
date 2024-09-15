<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class ParserTest extends TestCase
{
    public function testParser()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $json = file_get_contents(__DIR__ . '/data/' . $query . '.json');
        $expected = json_decode($json);
        $parser = new Parser($lookup, $query);
        $actual = (string)$parser->get();
        $actual = json_decode($actual);
        $this->assertEquals($expected, $actual);
    }
}
