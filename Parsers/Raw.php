<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Str;

class Raw
{
    public function __invoke(string $lookup): array
    {
        $lines = explode(PHP_EOL, $lookup);
        $raw = [];
        $hasKeys = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ':')) {
                [$key, $value] = explode(':', $line, 2);
                $key = Str::camel($key);
                $value = trim($value);
                if (str_contains(strtolower($key), 'date')) {
                    try {
                        $value = Carbon::parse($value);
                    } catch (InvalidFormatException $exception) {
                        //
                    }
                } elseif (str_contains(strtolower($key), 'url')) {
                    $value = strtolower(filter_var($value, FILTER_VALIDATE_URL | FILTER_VALIDATE_DOMAIN));
                } elseif (str_contains(strtolower($key), 'email')) {
                    $filter = strpos($value, '@') ? FILTER_VALIDATE_EMAIL : FILTER_VALIDATE_URL;
                    $value = filter_var(strtolower($value), $filter) ?: '';
                } elseif (str_contains(strtolower($key), 'server')) {
                    $value = trim(strtolower(filter_var($value, FILTER_VALIDATE_DOMAIN)), '.');
                }
                if (in_array($key, $hasKeys)) {
                    if (is_array($raw[$key]) === false) {
                        $firstValue = $raw[$key];
                        $raw[$key] = [];
                        $raw[$key][] = $firstValue;
                    }
                    if ($value) {
                        $raw[$key][] = $value;
                    }
                } else {
                    $raw[$key] = $value;
                    if (in_array($key, $hasKeys) === false) {
                        $hasKeys[] = $key;
                    }
                }
            } else {
                $raw[] = $line;
            }
        }
        return array_filter($raw);
    }
}
