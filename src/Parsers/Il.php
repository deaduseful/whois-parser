<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

class Il extends Generic
{
    protected ?string $dateFormat = 'Ymd|'; // eg: registrar AT ns.il 19990605 Assigned;
    protected array $mapFields = [
        'changed' => 'updated',
    ];
    protected function parseDate(string $dateString): ?Carbon
    {
        $pattern = '#(\d{4}[0-3]\d[0-3]\d)#';
        if (preg_match($pattern, $dateString, $matches)) {
            $dateTime = $matches[0];
            $carbon = Carbon::createFromFormat($this->dateFormat, $dateTime);
            if ($carbon->unix() < 0) {
                return null;
            }
            return $carbon;
        }
        return null;
    }
}
