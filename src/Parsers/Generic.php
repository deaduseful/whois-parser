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
        'status' => 'status',
        'dnssec' => 'dnssec',
        'holder' => 'registrar',
        'admin-c' => 'admin',
        'tech-c' => 'tech',
        'billing-c' => 'billing',
    ];

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
