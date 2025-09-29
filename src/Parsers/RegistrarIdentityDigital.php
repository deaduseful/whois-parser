<?php

namespace Deaduseful\WhoisParser\Parsers;

/**
 * Donuts Inc. and Afilias, Inc. Rebrand to Identity Digital
 * @see https://www.prnewswire.com/news-releases/donuts-inc-and-afilias-inc-rebrand-to-identity-digital-301572401.html
 */
class RegistrarIdentityDigital extends Generic
{
    protected array $mapFields = [
        'createdDate' => self::DATE_CREATED,
        'creationDate' => self::DATE_CREATED,
        'updatedDate' => self::DATE_UPDATED,
        'registrarRegistrationExpirationDate' => self::DATE_EXPIRY,
        'registryExpiryDate' => self::DATE_EXPIRY,
    ];
    protected array $matchNotFound = [
        'Domain not found.',
    ];

    protected array $matchRateLimit = [
        'WHOIS LIMIT EXCEEDED',
        'Query rate exceeded, please try again later.',
    ];

    protected array $matchReserved = [
        'This name is reserved by the Registry.',
    ];

    public function isNotFound(): bool
    {
        return $this->getUpdated($this->parse()) === null;
    }
}
