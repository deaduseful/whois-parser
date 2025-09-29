<?php

namespace Deaduseful\WhoisParser\Tests;

use Carbon\Carbon;
use Deaduseful\WhoisParser\Parser;


class ExpiryDateTest extends TestCase
{
    /** @dataProvider expiryData */
    public function testParseExpiryDateData(string $domain, ?string $expected = null, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $data = $parser->get();
        $result = $data->expiryDate();
        $expected = $expected ? Carbon::parse($expected) : null;
        self::assertEquals($expected, $result);
    }

    public function expiryData(): array
    {
        return [
            ['michael-bredahl.dk', '2020-02-29 00:00:00'],
            ['cfa.ge', '2023-09-18 00:00:00'],
            ['salvationdata.id', '2023-10-09 00:09:10'],
            ['multi-net.su', '2024-03-02 00:00:00'],
            ['lka.net.au', null],
            ['prediksijitu.site', '2024-04-05 18:47:03.00'],
            ['downloader.social', '2023-12-11 10:51:02'],
            ['cohenhouse.it', '2023-08-21 00:00:00'],
            ['me.rs', '2019-06-08 11:42:48'],
            ['howardsbathspa.org', '2024-04-08 00:30:26'],
            ['ru.ac.th', '2030-08-25 00:00'],
            ['smsbomber.co.in', '2023-09-20 10:23:51'],
            ['mana.md', '2026-01-05'],
            ['muxcdn.net', '2024-05-08T09:08:49'],
            ['makoons.com', '2025-09-08T05:42:39Z', 'whois.PublicDomainRegistry.com'],
            ['us-alphatonic.info', '2024-04-06T11:12:13Z'],
            ['msnet.co.kr', '2024-01-27'],
            ['google.pt', '28-02-2023 23:59:00'],
            ['tokobungaindonesia.com', '2024-05-29T04:52:11Z', 'whois.webnic.cc'],
            ['dinamoon.pl', null],
            ['toko.ly', '2022-01-21 08:50:34'],
            ['zara', null],
        ];
    }
}
