<?php

namespace Deaduseful\WhoisParser\Parsers;

/**
 * Constants for config-based parsers
 * 
 * Using constants instead of string literals provides:
 * - IDE support (go-to-definition, find usages, refactoring)
 * - Type safety and compile-time checks
 * - Easy refactoring - change once, updates everywhere
 * - Clear documentation of available config-based parsers
 */
class Parsers
{
    // Country code TLD parsers
    const AR = 'Ar';
    const AT = 'At';
    const BE = 'Be';
    const BG = 'Bg';
    const BN = 'Bn';
    const BO = 'Bo';
    const BY = 'By';
    const CH = 'Ch';
    const CR = 'Cr';
    const CZ = 'Cz';
    const DK = 'Dk';
    const ES = 'Es';
    const EU = 'Eu';
    const FI = 'Fi';
    const GE = 'Ge';
    const HK = 'Hk';
    const HR = 'Hr';
    const HU = 'Hu';
    const ID = 'Id';
    const IM = 'Im';
    const IS = 'Is';
    const IT = 'It';
    const JP = 'Jp';
    const KG = 'Kg';
    const KR = 'Kr';
    const LT = 'Lt';
    const LU = 'Lu';
    const LV = 'Lv';
    const LY = 'Ly';
    const MD = 'Md';
    const MOSCOW = 'Moscow';
    const MX = 'Mx';
    const NC = 'Nc';
    const NO = 'No';
    const NZ = 'Nz';
    const QA = 'Qa';
    const SA = 'Sa';
    const SM = 'Sm';
    const ST = 'St';
    const TH = 'Th';
    const TW = 'Tw';
    const UZ = 'Uz';
    const YE = 'Ye';
    
    // Generic TLD parsers
    const GDN = 'Gdn';
    
    // NIC parsers
    const NIC_CN = 'NicCn';
    const NIC_DE = 'NicDe';
    const NIC_GOOGLE = 'NicGoogle';
    
    // Registrar parsers
    const REGISTRAR_AF_NIC = 'RegistrarAfNic';
    const REGISTRAR_GODADDY = 'RegistrarGodaddy';
    const REGISTRAR_REGISTRY_ASP = 'RegistrarRegistryAsp';
    const REGISTRAR_SIDN = 'RegistrarSidn';
    
    // Registry parsers
    const REGISTRY_INDIA = 'RegistryIndia';
    
    // Brand/Corporate parsers
    const SAMSUNG = 'Samsung';
    const SB = 'Sb';
    const SCB = 'Scb';
    
    // WHOIS service parsers
    const WHOIS_1_API = 'Whois1Api';
    const WHOIS_AIT_DOMAINS = 'WhoisAitDomains';
    const WHOIS_DYNADOT = 'WhoisDynadot';
    const WHOIS_ENAME = 'WhoisEname';
    const WHOIS_FREENOMS = 'WhoisFreenoms';
    const WHOIS_IANA = 'WhoisIana';
    const WHOIS_JOKER = 'WhoisJoker';
    const WHOIS_MASTER_NAME = 'WhoisMasterName';
    const WHOIS_NAME_SRS = 'WhoisNameSrs';
    const WHOIS_NETWORK_SOLUTIONS = 'WhoisNetworkSolutions';
    const WHOIS_PUBLIC_DOMAIN_REGISTRY = 'WhoisPublicDomainRegistry';
    const WHOIS_WEB_NIC = 'WhoisWebNic';
    const WHOIS_WEBMASTERS = 'WhoisWebmasters';
}

