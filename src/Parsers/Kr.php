<?php

namespace Deaduseful\WhoisParser\Parsers;

class Kr extends Generic
{
    protected array $matchNotFound = [
        'The requested domain was not found in the Registry or Registrar’s WHOIS Server.',
    ];
    protected ?string $dateFormat = 'Y. m. d.|'; // eg: 2024. 01. 27.
    protected array $mapFields = [
        'registeredDate' => self::DATE_CREATED,
        'lastUpdatedDate' => self::DATE_UPDATED,
        'expirationDate' => self::DATE_EXPIRY,
    ];
}
