<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Illuminate\Support\Str;

class KeyValue implements Strategy
{
    const ERROR_DOMAIN_NOT_FOUND = 'Domain not found';
    const ERROR_RATE_LIMITED = 'Rate limited';
    protected array $matchNotFound = [];
    protected array $matchRateLimit = [];
    protected ?string $lookup;

    protected ?string $error;

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

    public function getError(): ?string
    {
        return $this->error;
    }


    public function parseRateLimit(): bool
    {
        $lookup = $this->lookup;
        foreach ($this->matchRateLimit as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                $this->error = self::ERROR_RATE_LIMITED;
                return true;
            }
        }
        return false;
    }

    public function isNotFound(): bool
    {
        foreach ($this->matchNotFound as $find) {
            if (str_starts_with($this->lookup, $find)) {
                $this->error = self::ERROR_DOMAIN_NOT_FOUND;
                return true;
            }
        }
        $rawData = $this->parse();
        if (empty($rawData['notFound']) === false) {
            $this->error = self::ERROR_DOMAIN_NOT_FOUND;
            return true;
        }
        return false;
    }
}
