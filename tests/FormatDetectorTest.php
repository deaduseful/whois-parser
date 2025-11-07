<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parsers\FormatDetector;
use Deaduseful\WhoisParser\Parsers\RegistrarAfNic;
use Deaduseful\WhoisParser\Parsers\RegistrarAmazon;
use Deaduseful\WhoisParser\Parsers\RegistrarCentralNic;
use Deaduseful\WhoisParser\Parsers\RegistrarChannelIsles;
use Deaduseful\WhoisParser\Parsers\RegistrarGodaddy;
use Deaduseful\WhoisParser\Parsers\RegistrarIdentityDigital;
use Deaduseful\WhoisParser\Parsers\RegistrarNominet;
use Deaduseful\WhoisParser\Parsers\RegistrarRyce;
use Deaduseful\WhoisParser\Parsers\RegistrarSidn;
use Deaduseful\WhoisParser\Parsers\RegistrarUniregistry;
use Deaduseful\WhoisParser\Parsers\RegistrarVerisignGrs;
use Deaduseful\WhoisParser\Parsers\RegistryAfrica;
use Deaduseful\WhoisParser\Parsers\RegistryIndia;
use Deaduseful\WhoisParser\Parsers\SgNic;
use Deaduseful\WhoisParser\Parsers\WhoisTcinetRu;

class FormatDetectorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        FormatDetector::clearStrategies();
    }

    /**
     * @dataProvider detectionCases
     */
    public function testDetectsKnownFormats(string $fixture, string $expectedClass): void
    {
        $lookup = file_get_contents($fixture);

        $detected = FormatDetector::detect($lookup);

        self::assertSame(
            $expectedClass,
            $detected,
            sprintf('Failed asserting detection for fixture %s', basename($fixture))
        );
    }

    public function detectionCases(): array
    {
        return [
            'nominet' => [
                self::fixture('test.co.uk.txt'),
                RegistrarNominet::class,
            ],
            'tcinet ru' => [
                self::fixture('taken/nic.ru.txt'),
                WhoisTcinetRu::class,
            ],
            'identity digital' => [
                self::fixture('taken/nic.xxx.txt'),
                RegistrarIdentityDigital::class,
            ],
            'identity digital not found' => [
                self::fixture('notfound/99a0cf3fce1c579916612fce551f7c30.abb.txt'),
                RegistrarIdentityDigital::class,
            ],
            'centralnic not found' => [
                self::fixture('notfound/99a0cf3fce1c579916612fce551f7c30.ws.txt'),
                RegistrarCentralNic::class,
            ],
            'centralnic reserved' => [
                self::fixture('reserved/test.top.txt'),
                RegistrarCentralNic::class,
            ],
            'afnic not found' => [
                self::fixture('notfound/99a0cf3fce1c579916612fce551f7c30.yt.txt'),
                RegistrarAfNic::class,
            ],
            'afnic taken' => [
                self::fixture('taken/nic.fr.txt'),
                RegistrarAfNic::class,
            ],
            'sgnic' => [
                self::fixture('pupsik.sg.txt'),
                SgNic::class,
            ],
            'verisign grs not found' => [
                self::fixture('dns-001.com.txt'),
                RegistrarVerisignGrs::class,
            ],
            'godaddy' => [
                self::fixture('taken/godaddy.com.txt'),
                RegistrarGodaddy::class,
            ],
            'uniregistry' => [
                self::fixture('notfound/99a0cf3fce1c579916612fce551f7c30.sexy.txt'),
                RegistrarUniregistry::class,
            ],
            'sidn not found' => [
                self::fixture('notfound/99a0cf3fce1c579916612fce551f7c30.nl.txt'),
                RegistrarSidn::class,
            ],
            'sidn taken' => [
                self::fixture('taken/nic.politie.txt'),
                RegistrarSidn::class,
            ],
            'africa registry' => [
                self::fixture('notfound/99a0cf3fce1c579916612fce551f7c30.za.txt'),
                RegistryAfrica::class,
            ],
            'india registry' => [
                self::fixture('reserved/99a0cf3fce1c579916612fce551f7c30.xn--xkc2dl3a5ee0h.txt'),
                RegistryIndia::class,
            ],
            'amazon registry' => [
                self::fixture('taken/nic.zero.txt'),
                RegistrarAmazon::class,
            ],
            'channel islands' => [
                self::fixture('test.gg.txt'),
                RegistrarChannelIsles::class,
            ],
            'ryce' => [
                self::fixture('notfound/99a0cf3fce1c579916612fce551f7c30.wien.txt'),
                RegistrarRyce::class,
            ],
        ];
    }

    private static function fixture(string $relativePath): string
    {
        return __DIR__ . '/data/' . ltrim($relativePath, '/');
    }

    public function testDetectReturnsNullForEmptyLookup(): void
    {
        self::assertNull(FormatDetector::detect(''));
    }

    public function testDetectReturnsNullForUnknownFormat(): void
    {
        $lookup = "example domain without known patterns";

        self::assertNull(FormatDetector::detect($lookup));
    }
}

