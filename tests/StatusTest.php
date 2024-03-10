<?php

namespace Deaduseful\WhoisParser\tests;

use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class StatusTest extends TestCase
{
    public function testStatus()
    {
        $query = 'fiat';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $parser = new Parser($lookup, $query, 'whois.iana.org');
        $output = $parser->getOutput();
        $this->assertEquals('FORMER', $output->registration->statuses[0]);
    }

    public function testStatusClientTransferProhibited()
    {
        $query = 'woodchuck.app';
        $lookup = file_get_contents(__DIR__ . '/data/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $output = $parser->getOutput();
        $status = $output->hasStatus('clienttransferprohibited');
        $this->assertTrue($status);
    }
}
