<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Illuminate\Support\Str;

class KeyValue implements Strategy
{
    protected ?string $lookup;

    public function __construct(string $lookup)
    {
        $this->lookup = $lookup;
    }

    public function parse(): array
    {
        $lookup = $this->lookup;
        $lines = explode(PHP_EOL, $lookup);
        $raw = [];
        $hasKeys = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ':')) {
                [$key, $value] = explode(':', $line, 2);
                $key = $this->getCamel($key);
                $value = trim($value);
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

    private function getCamel(string $key): string
    {
        return Str::camel($key);
    }
}
