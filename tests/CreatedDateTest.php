<?php

namespace Deaduseful\WhoisParser\Tests;

use Carbon\Carbon;
use Deaduseful\WhoisParser\Parser;

class CreatedDateTest extends TestCase
{
    /**
     * @dataProvider expiryData
     */
    public function testParseCreationDateData(string $domain, ?string $expected = null, ?string $whoisServer = null)
    {
        $lookup = file_get_contents(__DIR__ . '/data/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain, $whoisServer);
        $data = $parser->get();
        $result = $data?->createdDate();
        $expected = $expected ? Carbon::parse($expected) : null;
        self::assertEquals($expected, $result, $domain);
    }

    public function expiryData(): array
    {
        return [
            ['ess.do', null],
            ['n.pl', '1999-03-04 12:00:00'],
            ['q.gs', '2011-02-16T07:58:03.114000+0000'],
            ['michael-bredahl.dk', '2011-02-20'],
            ['cfa.ge', '2020-09-17 00:00:00'],
            ['salvationdata.id', '2021-10-09T14:09:10'],
            ['multi-net.su', '2020-01-29T08:52:19'],
            ['lka.net.au', null],
            ['prediksijitu.site', '2023-04-05T18:47:03'],
            ['downloader.social', '2022-12-11 10:51:02'],
            ['cohenhouse.it', '2020-08-21T22:50:48'],
            ['me.uk', null],
            ['me.rs', '2009-06-08 11:42:48'],
            ['giveaway.gg', '2020-06-03 18:06:52.856'],
            ['expressofritz.com.br', '2022-08-03 00:00'],
            ['aytitekstil.com.tr', '2011-Apr-27 00:00'],
            ['ru.ac.th', '1999-08-26 00:00'],
            ['mpudahuel.cl', '2000-11-16T12:30:06-0300'],
            ['pupsik.sg', '16-Feb-2023 14:35:35'],
            ['mana.md', '2005-01-05'],
            ['skyconnect.tn', '04-04-2022 11:12:03 GMT+1'],
            ['birou-traduceri-constanta.ro', '2023-02-20'],
            ['test.ro', null],
            ['officecomsetup.support', '2020-07-10 13:28:44'],
            ['3xxx.info', '2023-02-25 18:20:31'],
            ['clickbank.net', '1997-07-11 04:00:00', 'whois.1api.net'],
            ['sausage.io', '2015-01-26 21:16:56'],
            ['papatyadernegi.org', '2023-02-17 11:14:13', 'whois.porkbun.com'],
            ['test.je', '31st December 2001 at 00:00:00.000'],
            ['test.ax', '19.09.2020'],
            ['bank.gov.ua', null],
            ['confido.as', '08th March 2023 at 12:54:30.219'],
            ['salsalessonschicago.org', '2004-01-28T00:03:01Z', 'whois.aitdomains.com'],
            ['google.co.ug', '2016-02-17'],
            ['spb24.net', '2009-07-22', 'whois.mastername.ru'],
            ['zara', '2015-09-10'],
            ['amid.org', '2009-03-31T14:30:31Z', 'whois.uniregistrar.com'],
        ];
    }
}
