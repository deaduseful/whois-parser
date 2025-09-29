<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;
use Tests\TestCase;

class StatusTest extends TestCase
{
    public function testStatus()
    {
        $query = 'fiat';
        $lookup = file_get_contents(__DIR__ . '/data/status/' . $query . '.txt');
        $parser = new Parser($lookup, $query, 'whois.iana.org');
        $output = $parser->get();
        $this->assertEquals('FORMER', $output->registration->statuses[0]);
    }

    public function testStatusClientTransferProhibited()
    {
        $query = 'news.app';
        $lookup = file_get_contents(__DIR__ . '/data/status/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $output = $parser->get();
        $status = $output->hasStatus('clienttransferprohibited');
        $this->assertTrue($status);
    }

    public function testStatusPendingDelete()
    {
        $query = 'trademark.co';
        $lookup = file_get_contents(__DIR__ . '/data/status/' . $query . '.txt');
        $parser = new Parser($lookup, $query);
        $output = $parser->get();
        $this->assertTrue($output->hasStatus('pendingDelete'));
        $this->assertTrue($output->hasStatus('redemptionPeriod'));
        $this->assertFalse($output->hasStatus('clienttransferprohibited'));
    }
}
