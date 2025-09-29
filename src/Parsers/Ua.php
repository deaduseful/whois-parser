<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class Ua extends Generic
{
    protected ?string $dateFormat = '*???* YmdHis'; // eg: UARR149-UANIC 20230330112321 -> 2023-03-30 11:23:21
    protected array $mapFields = [
        'created' => 'created',
        'changed' => 'updated',
        'nserver' => 'nameServer',
    ];
    protected function parseDate(string $dateString): ?Carbon
    {
        $pattern = '#^\w+-UANIC \d{4}[01]\d[0-3]\d[0-2]\d[0-5]\d[0-5]\d$#';
        if (preg_match($pattern, $dateString, $matches)) {
            $dateTime = $matches[0];

            // Handle case where date is all zeros (like "0-UANIC 00000000000000")
            if (str_contains($dateTime, '00000000000000')) {
                return null;
            }

            $carbon = Carbon::createFromFormat($this->dateFormat, $dateTime);
            if ($carbon->unix() < 0) {
                return null;
            }
            return $carbon;
        }
        return null;
    }

    protected array $matchNotFound = [
        '% No entries found for',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        $lastElement = array_pop($rawData);
        return str_starts_with($lastElement, $this->matchNotFound[0]);
    }
}
