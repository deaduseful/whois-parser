<?php

namespace Deaduseful\WhoisParser\Tests;

use Deaduseful\WhoisParser\Parser;

class NotFoundTest extends TestCase
{
    /**
     * @dataProvider notFoundData
     */
    public function testNotFound(string $domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/notfound/' . $domain . '.txt');
        /*if (str_starts_with($lookup, 'No match for')) {
            $tld = pathinfo($domain, PATHINFO_EXTENSION);
            $content = "'$tld' => RegistrarVerisignGrs::class," . PHP_EOL;
            file_put_contents(__DIR__ . '/data/_map_tlds.txt', $content, FILE_APPEND);
        }*/
        $parser = new Parser($lookup, $domain);
        $output = $parser->get();
        self::assertNull($output, $domain);
        $expected = 'Not found';
        self::assertEquals($expected, $parser->getError(), $domain);
    }

    public function notFoundData(): array
    {
        $glob = glob(__DIR__ . '/data/notfound/*.txt');
        return array_map(function ($value) {
            return [str_replace('.txt', '', basename($value))];
        }, $glob);
    }

    /**
     * @dataProvider takenData
     */
    public function testFound(string $domain)
    {
        $lookup = file_get_contents(__DIR__ . '/data/taken/' . $domain . '.txt');
        $parser = new Parser($lookup, $domain);
        $output = $parser->get();
        self::assertNull($parser->getError(), $domain);
        self::assertIsObject($output, $domain);
    }

    public function takenData(): array
    {
        $glob = glob(__DIR__ . '/data/taken/*.txt');
        return array_map(function ($value) {
            return [str_replace('.txt', '', basename($value))];
        }, $glob);
    }

    public function testIsAvailable()
    {
        $lookup = file_get_contents(__DIR__ . '/data/xexample.ru.txt');
        $parser = new Parser($lookup, 'example.ru', 'whois.nic.ru');
        self::assertTrue($parser->isNotFound());
    }
}
