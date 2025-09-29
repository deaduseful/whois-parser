<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;
use Tests\TestCase;

class ResponseVersionTest extends TestCase
{
    public function testResponseIncludesVersion()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $result = $parser->get();
        
        // Check that the response includes parser version information
        $this->assertNotNull($result->parser_version, 'Response should include parser_version field');
        $this->assertIsString($result->parser_version, 'Parser version should be a string');
        $this->assertEquals('1.0.0', $result->parser_version, 'Parser version should match version');
    }

    public function testResponseVersionInJsonOutput()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $result = $parser->get();
        
        // Convert to JSON and decode to check structure
        $jsonString = (string) $result;
        $data = json_decode($jsonString, true);
        
        // Check that parser_version is included in the JSON output
        $this->assertArrayHasKey('parser_version', $data, 'JSON output should include parser_version field');
        $this->assertEquals('1.0.0', $data['parser_version'], 'Parser version in JSON should match version');
    }

    public function testResponseAllMethodIncludesVersion()
    {
        $query = 'domaintools.com';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $result = $parser->get();
        
        // Check that the all() method includes parser_version
        $allData = $result->all();
        $this->assertObjectHasProperty('parser_version', $allData, 'all() method should include parser_version property');
        $this->assertEquals('1.0.0', $allData->parser_version, 'Parser version in all() should match version');
    }
}
