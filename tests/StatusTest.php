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
        $parser = new Parser($lookup);
        $this->markTestIncomplete('@todo needs to be done');
    }
}
