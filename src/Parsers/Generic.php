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
        'No Object Found',
        'No match for',
        'NOT FOUND',
        'No entries found for the selected source(s)',
        'No entries found',
        'Object not found',
        'Object does not exist',
        'Domain not found',
        'Domain not found in database',
        'Domain name not found',
        'Domain not found in database',
        'Domain name not registered',
        'Domain not registered',
        'Domain not found in registry',
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
        if (isset($rawData[$this->statusField])
            && in_array($rawData[$this->statusField], $this->matchNotFound, true)) {
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
