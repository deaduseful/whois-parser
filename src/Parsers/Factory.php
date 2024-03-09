<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Factory
{
    private const TLD_MAP = [
        'au' => Au::class,
        'ch' => Ch::class,
        'cz' => Cz::class,
        'es' => Es::class,
        'in' => In::class,
        'nz' => Nz::class,
        'org' => Org::class,
        'qa' => Qa::class,
        'vip' => Vip::class,
        'se' => Se::class,
        'kz' => Kz::class,
        'tr' => Tr::class,
        'it' => It::class,
        'online' => Online::class,
        'xyz' => Xyz::class,
        'fr' => Fr::class,
        'il' => Il::class,
    ];
    private const WHOIS_SERVER_MAP = [
        'whois.registry.in' => In::class,
        'whois.joker.com' => WhoisJoker::class,
        'whois.godaddy.com' => WhoisGodaddy::class,
        'whois.networksolutions.com' => WhoisNetworkSolutions::class,
        'whois.webmasters.com' => WhoisWebmasters::class,
    ];

    public static function create(string $lookup, string $tld = null, string $whoisServer = null): Strategy
    {
        if ($whoisServer) {
            if (isset(self::WHOIS_SERVER_MAP[$whoisServer])) {
                $class = self::WHOIS_SERVER_MAP[$whoisServer];
                return new $class($lookup);
            }
        }
        if ($tld) {
            if (isset(self::TLD_MAP[$tld])) {
                $class = self::TLD_MAP[$tld];
                return new $class($lookup);
            }
        }
        return new Generic($lookup);
    }
}
