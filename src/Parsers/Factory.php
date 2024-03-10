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
        'ua' => Ua::class,
        'su' => Su::class,
        'sg' => Sg::class,
        'id' => Id::class,
        'fi' => Fi::class,
        'pf' => Pf::class,
        'bn' => Bn::class,
        'ru' => Ru::class,
        'pl' => Pl::class,
        'social' => Social::class,
        'rs' => Rs::class,
        'gg' => Gg::class,
        'br' => Br::class,
        'th' => Th::class,
        'cl' => Cl::class,
        'md' => Md::class,
        'ro' => Ro::class,
        'dk' => Dk::class,
        'ge' => Ge::class,
        'info' => Info::class,
        'kr' => Kr::class,
        'pt' => Pt::class,
    ];
    private const WHOIS_SERVER_MAP = [
        'whois.registry.in' => In::class,
        'whois.joker.com' => WhoisJoker::class,
        'whois.godaddy.com' => WhoisGodaddy::class,
        'whois.networksolutions.com' => WhoisNetworkSolutions::class,
        'whois.webmasters.com' => WhoisWebmasters::class,
        'whois.uniregistrar.net' => WhoisUniregistrar::class,
        'whois.one.com' => WhoisOne::class,
        'whois.global-village.de' => WhoisGlobalVillage::class,
        'whois.iana.org' => WhoisIana::class,
        'whois.publicdomainregistry.com' => WhoisPublicDomainRegistry::class,
        'whois.webnic.cc' => WhoisWebNic::class,
    ];

    public static function create(string $lookup, string $query = null, string $whoisServer = null): Strategy
    {
        if ($whoisServer) {
            $whoisServer = strtolower($whoisServer);
            if (isset(self::WHOIS_SERVER_MAP[$whoisServer])) {
                $class = self::WHOIS_SERVER_MAP[$whoisServer];
                return new $class($lookup);
            }
        }
        if (strpos($query, '.')) {
            $tld = self::parseTld($query);
            if (isset(self::TLD_MAP[$tld])) {
                $class = self::TLD_MAP[$tld];
                return new $class($lookup);
            }
        }
        return new Generic($lookup);
    }

    private static function parseTld(string $query): ?string
    {
        if (strpos($query, '.')) {
            return pathinfo(strtolower($query), PATHINFO_EXTENSION);
        }
        return null;
    }
}
