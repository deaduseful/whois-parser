<?php

namespace Deaduseful\WhoisParser\Tests;

use Carbon\Carbon;
use Deaduseful\WhoisParser\Parser;

class UpdatedDateTest extends TestCase
{
    /**
     * @dataProvider updatedData
     */
    public function testParseUpdatedDateData(string $domain, ?string $expected = null, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $data = $parser->get();
        $result = $data->updatedDate();
        $expected = $expected ? Carbon::parse($expected) : null;
        self::assertEquals($expected, $result, $domain);
    }

    public function updatedData(): array
    {
        return [
            ['allsvenskan2023.se'],
            ['alprof.kz', '2017-11-16 09:40:04'],
            ['aname.net', '2022-11-02T21:31:09'],
            ['appraiseyourart.com', '2019-03-15 19:35:08', 'whois.webmasters.com'],
            ['aytitekstil.com.tr', '2023-04-07T13:16:47+03:00'],
            ['bestcasinotoplist.online', '2022-12-09T21:50:15.00Z', 'whois.namesrs.com'],
            ['cohenhouse.it', '2022-09-06 00:52:47'],
            ['covid-19.xyz', '2023-04-26 08:57:22.0Z'],
            ['downloader.social', '2022-12-11 10:51:02'],
            ['euros-en-or.fr', '2018-05-22T15:11:11Z'],
            ['google.co.il', '1999-06-05'],
            ['intercede.app', '2023-12-19T20:51:33Z'],
            ['kmu.gov.ua', '2023-03-30 11:23:21'],
            ['lebbook.kz'],
            ['lka.net.au'],
            ['makoons.com', '2022-09-03 04:14:46'],
            ['mana.md'],
            ['multi-net.su', '2023-04-06 13:36:31'],
            ['nma.com', '2020-03-14T17:43:37Z', 'whois.uniregistrar.net'],
            ['prediksijitu.site'], // 0001-01-01T00:00:00.00Z
            ['pupsik.sg', '07-04-2023 08:41:54'],
            ['salvationdata.id', '2022-10-10 04:09:10'],
            ['sportsbetscript.com', '2019-10-27T00:12:43Z', 'whois.one.com'],
            ['test.fi', '7.9.2017 17:47:38'],
            ['underberg.com', '2019-07-29 07:04:51', 'whois.global-village.de'],
            ['vini.pf', '2022-05-27'],
            ['woodchuck.app', '2023-11-16T08:09:01Z'],
            ['www.gov.bn', '02-Feb-2018 16:45:04'],
            ['test.online', '2024-01-15T00:04:29.0Z'],
            ['continuity.com', null],
            ['zara', '2023-08-04', 'whois.iana.org'],
        ];
    }
}
