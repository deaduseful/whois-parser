<?php

namespace Deaduseful\WhoisParser\tests;

use Carbon\Carbon;
use Deaduseful\WhoisParser\Parser;
use Tests\TestCase;
class UpdatedDateTest extends TestCase
{
    /** @dataProvider updatedData */
    public function testParseUpdatedDateData($domain, $expected)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup);
        $data = $parser->getData();
        $result = $data->response->parsed_whois->updated_date;
        $expected = $expected ? Carbon::parse($expected)->toAtomString() : null;
        self::assertEquals($expected, $result);
    }

    public function updatedData(): array
    {
        return [
            ['allsvenskan2023.se', null],
            ['alprof.kz', '2017-11-16 09:40:04'],
            ['aname.net', '2022-11-02T21:31:09.00Z'],
            ['appraiseyourart.com', '2019-03-15 19:35:08'],
            ['aytitekstil.com.tr', '2023-04-07T13:16:47+03:00'],
            ['bestcasinotoplist.online', '2022-12-09T21:50:15.00Z'],
            ['cohenhouse.it', '2022-09-06 00:52:47'],
            ['covid-19.xyz', '2023-04-26 08:57:22.0Z'],
            ['downloader.social', '2022-12-11 10:51:02'],
            ['euros-en-or.fr', '2015-06-10T22:07:25Z'],
            ['google.co.il', '1999-06-05'],
            ['intercede.app', '2023-12-19T20:51:33Z'],
            ['kmu.gov.ua', '2023-03-30 11:23:21'],
            ['lebbook.kz', null],
            ['lka.net.au', null],
            ['makoons.com', '2022-09-03 04:14:46'],
            ['mana.md', null],
            ['multi-net.su', '2023-04-06 13:36:31'],
            ['nma.com', '2020-03-14T17:43:37Z'],
            ['prediksijitu.site', null],
            ['pupsik.sg', '07-04-2023 08:41:54'],
            ['salvationdata.id', '2022-10-10 04:09:10'],
            ['sportsbetscript.com', '2019-10-27T00:12:43Z'],
            ['test.fi', '7.9.2017 17:47:38'],
            ['underberg.com', '2019-07-29 07:04:51'],
            ['vini.pf', '2022-05-27'],
            ['woodchuck.app', '2023-11-16T08:09:01Z'],
            ['www.gov.bn', '02-Feb-2018 16:45:04'],
        ];
    }
}
