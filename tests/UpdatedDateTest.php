<?php

namespace Deaduseful\WhoisParser\tests;

use Carbon\Carbon;
use Deaduseful\WhoisParser\src\Parser;
use Tests\TestCase;

class UpdatedDateTest extends TestCase
{
    /** @dataProvider updatedData */
    public function testParseUpdatedDateData(string $domain, ?string $expected = null, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $data = $parser->getOutput();
        $result = $data->updatedDate();
        $expected = $expected ? Carbon::parse($expected) : null;
        self::assertEquals($expected, $result);
    }

    public function updatedData(): array
    {
        return [
            ['allsvenskan2023.se', null, null],
            ['alprof.kz', '2017-11-16 09:40:04', null],
            ['aname.net', null, null],
            ['appraiseyourart.com', '2019-03-15 19:35:08', 'whois.webmasters.com'],
            ['aytitekstil.com.tr', '2023-04-07T13:16:47+03:00', null],
            ['bestcasinotoplist.online', '2022-12-09T21:50:15.00Z', null],
            ['cohenhouse.it', '2022-09-06 00:52:47', null],
            ['covid-19.xyz', '2023-04-26 08:57:22.0Z', null],
            ['downloader.social', '2022-12-11 10:51:02', null],
            ['euros-en-or.fr', '2018-05-22T15:11:11Z', null],
            ['google.co.il', '1999-06-05', null],
            ['intercede.app', '2023-12-19T20:51:33Z', null],
            ['kmu.gov.ua', '2023-03-30 11:23:21', null],
            ['lebbook.kz', null, null],
            ['lka.net.au', null, null],
            ['makoons.com', '2022-09-03 04:14:46', null],
            ['mana.md', null, null],
            ['multi-net.su', '2023-04-06 13:36:31', null],
            ['nma.com', '2020-03-14T17:43:37Z', 'whois.uniregistrar.net'],
            ['prediksijitu.site', null, null], // 0001-01-01T00:00:00.00Z
            ['pupsik.sg', '07-04-2023 08:41:54', null],
            ['salvationdata.id', '2022-10-10 04:09:10', null],
            ['sportsbetscript.com', '2019-10-27T00:12:43Z', 'whois.one.com'],
            ['test.fi', '7.9.2017 17:47:38', null],
            ['underberg.com', '2019-07-29 07:04:51', 'whois.global-village.de'],
            ['vini.pf', '2022-05-27', null],
            ['woodchuck.app', '2023-11-16T08:09:01Z', null],
            ['www.gov.bn', '02-Feb-2018 16:45:04', null],
        ];
    }
}
