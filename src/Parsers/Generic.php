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
        'in' => 'No Data Found',
        'id' => 'DOMAIN NOT FOUND',
        'nominet' => 'This domain name has not been registered',
        'top' => 'The queried object does not exist',
        'do' => 'No Object Found',
        'fr' => '%% NOT FOUND',
        'cn' => 'No matching record.',
        'io' => 'Domain not found.',
        'as' => 'NOT FOUND',
        'bn' => 'Domain Not Found',
        'bo' => 'DOMINIO NO REGISTRADO',
        'by' => 'Object does not exist',
        'za' => 'Available',
        'cl' => 'no entries found.',
        'click' => 'is available for registration',
        //'cr,hr,ir,ls,mk,ve' => '%ERROR' => '101: no entries found'
        'gf' => 'NO OBJECT FOUND!',
        'mq' => 'NO OBJECT FOUND!',
        'gq' => 'The domain you requested is not known in Freenoms database.',
        'hk' => 'The domain has not been registered.',
        'hu' => 'Nincs talalat / No match',
        'im' => ' was not found.',
        'is' => '% No entries found for query ',
        //it 'status' => 'AVAILABLE'
        'jp' => 'No match!!',
        'kg' => 'Data not found. This domain is available for registration.',
        'lu' => '% No such domain',
        'mtr' => 'The domain has not been registered.',
        //lt 'status' => 'available'
        //lv 'status' => 'free'
        //lv 'status' => 'free'
        //edu 'nOMATCH' => '99a0cf3fce1c579916612fce551f7c30.edu'
        //hr '%ERROR' => 'No entries found'
        //is         5 => '% No entries found for query "99a0cf3fce1c579916612fce551f7c30.is".'
        'mx' => 'No_Se_Encontro_El_Objeto/Object_Not_Found',
        //name         24 => 'No match for "99A0CF3FCE1C579916612FCE551F7C30.NAME".'
        'nc' => 'No entries found in the .nc database',
        //'nl' =>         0 => '99a0cf3fce1c579916612fce551f7c30.nl is free'
        'no' => '% No match',
        //        14 => 'domain "99a0cf3fce1c579916612fce551f7c30.nu" not found.'
        //pl         1 => 'No information available about domain name 99a0cf3fce1c579916612fce551f7c30.pl in the Registry NASK database.'
        // politie         'status' => 'free'
        //pt         0 => '99a0cf3fce1c579916612fce551f7c30.pt - No Match'
        //rs         '%ERROR' => '103: Domain is not registered'
        //sa         3 => 'No Match for 99a0cf3fce1c579916612fce551f7c30.sa.'
        //se         14 => 'domain "99a0cf3fce1c579916612fce551f7c30.se" not found.'
        //si         27 => '% No entries found for the selected source(s).'
        //sm 'No entries found.'
        //st         0 => 'No entries found for domain 99a0cf3fce1c579916612fce551f7c30.st'
        //th         3 => '% No match found.'
        //tm         1 => 'Domain 99a0cf3fce1c579916612fce551f7c30.tm is available for purchase'
        //tr         0 => 'No match found for 99a0cf3fce1c579916612fce551f7c30.tr'
        //tw         0 => 'No Found'
        //ua         10 => '% No entries found for 99a0cf3fce1c579916612fce551f7c30.ua'
        //ug         5 => '>>> Domain not Found'
        //uk         3 => 'This domain name has not been registered.'
        //uz         3 => '% Domain names in the .uz domain can now be registered'
    ];

    /**
     * @var string[] Rate Limit Matching
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/limit-reached-messages.json
     */
    protected array $matchRateLimit = [
        'Query rate exceeded, please try again later.',
    ];

    protected array $mapFields = [
        'nserver' => 'nameserver',
        'creationDate' => 'created',
        'updatedDate' => 'updated',
        'registrarRegistrationExpirationDate' => 'expiry',
        'registrar' => 'registrar',
        'domainStatus' => 'status',
        'dnssec' => 'dnssec',
        'holder' => 'registrar',
        'admin-c' => 'admin',
        'tech-c' => 'tech',
        'billing-c' => 'billing',
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

    protected function getFilter(array $raw): array
    {
        $data = [];
        foreach ($raw as $key => $value) {
            if ($value) {
                if (isset($this->mapFields[$key])) {
                    $key = $this->mapFields[$key];
                }
                $data[$key] = $value;
            }
        }
        foreach ($this->dateFields as $dateField) {
            if (isset($data[$dateField])) {
                if (is_array($data[$dateField])) {
                    $data[$dateField] = array_shift($data[$dateField]);
                }
                $data[$dateField] = $this->parseDate($data[$dateField]);
            }
        }
        return parent::getFilter($data);
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
