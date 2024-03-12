<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Generic extends KeyValue
{
    /**
     * @var string[] Not Found Matching
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/whois.json
     */
    protected array $matchNotFound = [
        'Domain not found',
        'No match for',
        //'as' => 'NOT FOUND',
        'bn' => 'Domain Not Found',
        'bo' => 'DOMINIO NO REGISTRADO',
        'by' => 'Object does not exist',
        'za' => 'Available',
        'cl' => 'no entries found.',
        'gf' => 'NO OBJECT FOUND!',
        'mq' => 'NO OBJECT FOUND!',
        'gq' => 'The domain you requested is not known in Freenoms database.',
        'hk' => 'The domain has not been registered.',
        'hu' => 'Nincs talalat / No match',
        'jp' => 'No match!!',
        'kg' => 'Data not found. This domain is available for registration.',
        'lu' => '% No such domain',
        'mtr' => 'The domain has not been registered.',
        'mx' => 'No_Se_Encontro_El_Objeto/Object_Not_Found',
        'nc' => 'No entries found in the .nc database',
    ];

    /**
     * @var string[] Rate Limit Matching
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/limit-reached-messages.json
     */
    protected array $matchRateLimit = [
        'Query rate exceeded, please try again later.',
    ];

    public function isNotFound(): bool
    {
        foreach ($this->matchNotFound as $find) {
            if (str_starts_with($this->lookup, $find)) {
                return true;
            }
        }
        $rawData = $this->parse();
        foreach ($this->matchNotFound as $find) {
            if (in_array($find, $rawData, true)) {
                return true;
            }
            if (isset($rawData[0])
                && str_contains($rawData[0], $find)) {
                return true;
            }
        }
        if (empty($rawData['notFound']) === false) {
            return true;
        }
        return parent::isNotFound();
    }

    public function getCreated(array $rawData): ?Carbon
    {
        return $rawData[self::DATE_CREATED] ?? null;
    }

    public function getUpdated(array $rawData): ?Carbon
    {
        return $rawData[self::DATE_UPDATED] ?? null;
    }

    public function getExpiry(array $rawData): ?Carbon
    {
        return $rawData[self::DATE_EXPIRY] ?? null;
    }
}
