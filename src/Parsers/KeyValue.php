<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;
use Illuminate\Support\Str;

abstract class KeyValue implements Strategy
{
    protected ?string $dateFormat = null;
    const ERROR_DOMAIN_NOT_FOUND = 'Domain not found';
    const ERROR_RATE_LIMITED = 'Rate limited';
    protected array $matchNotFound = [];
    protected array $matchRateLimit = [];
    protected ?string $lookup;
    protected ?string $error;
    protected array $dateFields = [
        'created',
        'updated',
        'expiry',
    ];

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
                $key = $this->getCamel(trim($key, '.'));
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
        return $this->getFilter($raw);
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

    protected function getFilter(array $raw): array
    {
        return array_filter($raw);
    }

    protected function parseDate(string $dateString): ?Carbon
    {
        if (strlen($dateString) < 3) {
            return null;
        }
        if ($this->dateFormat) {
            return Carbon::createFromFormat($this->dateFormat, $dateString);
        }
        return Carbon::parse($dateString);
    }
}
