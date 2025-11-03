<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class TransferabilityTest extends TestCase
{
    /**
     * Data provider for transferability tests
     *
     * @return array<string, array{domain: string, directory: string, expectedTransferable: bool|null, description: string}>
     */
    public static function transferabilityProvider(): array
    {
        return [
            'clientTransferProhibited' => [
                'domain' => 'news.app',
                'directory' => 'status',
                'expectedTransferable' => false,
                'description' => 'Domain with clientTransferProhibited should not be transferable',
            ],
            'serverTransferProhibited' => [
                'domain' => 'nic.xyz',
                'directory' => 'taken',
                'expectedTransferable' => false,
                'description' => 'Domain with serverTransferProhibited should not be transferable',
            ],
            'status_ok' => [
                'domain' => 'nic.zip',
                'directory' => 'taken',
                'expectedTransferable' => true,
                'description' => 'Domain with status ok should be transferable',
            ],
            'turkey_locked' => [
                'domain' => 'trabis.gov.tr',
                'directory' => 'taken',
                'expectedTransferable' => false,
                'description' => 'Turkey domain with LOCKED transfer status should not be transferable',
            ],
            'sweden_unlocked' => [
                'domain' => 'nic.se',
                'directory' => 'taken',
                'expectedTransferable' => true,
                'description' => 'Sweden domain with registry-lock unlocked should be transferable',
            ],
            'redemptionPeriod' => [
                'domain' => 'trademark.co',
                'directory' => 'status',
                'expectedTransferable' => false,
                'description' => 'Domain in redemption period should not be transferable',
            ],
        ];
    }

    /**
     * @dataProvider transferabilityProvider
     */
    public function testTransferability(string $domain, string $directory, ?bool $expectedTransferable, string $description)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $directory . '/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain);
        $output = $parser->get();
        
        $this->assertNotNull($output, 'Parser should return a result for ' . $domain);
        $this->assertSame(
            $expectedTransferable,
            $output->isTransferable(),
            $description . ' (domain: ' . $domain . ')'
        );
    }

    /**
     * Test that the transferability record is properly populated
     */
    public function testTransferabilityRecordStructure()
    {
        $lookup = file_get_contents(__DIR__ . '/data/status/news.app.txt');
        $parser = new Parser($lookup, 'news.app');
        $output = $parser->get();
        
        $this->assertNotNull($output);
        $this->assertNotNull($output->transferability);
        $this->assertInstanceOf(
            \Deaduseful\WhoisParser\Records\Transferability::class,
            $output->transferability
        );
        $this->assertIsBool($output->transferability->transferable);
    }

    /**
     * Test that specific EPP status codes are detected
     */
    public function testEppStatusDetection()
    {
        // Test clientTransferProhibited
        $lookup = file_get_contents(__DIR__ . '/data/status/news.app.txt');
        $parser = new Parser($lookup, 'news.app');
        $output = $parser->get();
        
        $this->assertNotNull($output);
        $this->assertFalse($output->isTransferable());
        $this->assertTrue($output->hasStatus('clientTransferProhibited'));
        $this->assertTrue($output->hasStatus('clientUpdateProhibited'));
    }

    /**
     * Test that pendingDelete status is detected
     */
    public function testPendingDeleteDetection()
    {
        $lookup = file_get_contents(__DIR__ . '/data/status/trademark.co.txt');
        $parser = new Parser($lookup, 'trademark.co');
        $output = $parser->get();
        
        $this->assertNotNull($output);
        $this->assertFalse($output->isTransferable());
        $this->assertTrue($output->hasStatus('pendingDelete'));
        $this->assertTrue($output->hasStatus('redemptionPeriod'));
    }
}

