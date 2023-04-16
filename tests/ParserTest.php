<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\Parser;
use Tests\TestCase;
class ParserTest extends TestCase
{
    public function testParser()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $json = file_get_contents(__DIR__ . '/data/' . $query . '.json');
        $data = json_decode($json);
        $parser = new Parser($lookup);
        $this->assertEquals($parser->getData(), $data);
    }
}
