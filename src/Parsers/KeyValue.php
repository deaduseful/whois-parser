<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;
use Illuminate\Support\Str;

abstract class KeyValue implements Strategy
{
    const DATE_CREATED = 'created';
    const DATE_UPDATED = 'updated';
    const DATE_EXPIRY = 'expiry';
    protected ?string $dateFormat = null;
    protected array $matchNotFound = [];
    protected array $matchRateLimit = [];
    protected array $matchInvalid = [];

    protected ?string $lookup;
    protected ?string $error = null;
    protected array $dateFields = [
        self::DATE_CREATED,
        self::DATE_UPDATED,
        self::DATE_EXPIRY,
    ];

    protected string $statusField = 'domainStatus';

    public function __construct(string $lookup)
    {
        $this->lookup = $lookup;
    }

    public function parse(): array
    {
        $lookup = $this->lookup;
        $lines = $this->getLines($lookup);
        $raw = $this->getRaw($lines);
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

    public function isRateLimit(): bool
    {
        $lookup = $this->lookup;
        foreach ($this->matchRateLimit as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                return true;
            }
        }
        return false;
    }

    public function isInvalid(): bool
    {
        $lookup = $this->lookup;
        foreach ($this->matchInvalid as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                return true;
            }
        }
        return false;
    }

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
        }
        if (empty($rawData['notFound']) === false) {
            return true;
        }
        if (isset($rawData[$this->statusField]) && $rawData[$this->statusField] === 'No Object Found') {
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
            $carbon = Carbon::createFromFormat($this->dateFormat, $dateString);
        } else {
            $carbon = Carbon::parse($dateString);
        }
        if ($carbon->unix() > 0) {
            return $carbon;
        }
        return null;
    }

    protected function getLines(?string $lookup): array
    {
        return explode(PHP_EOL, $lookup);
    }

    private function getRaw(array $lines): array
    {
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
                    if ($value &&
                        in_array($value, $raw[$key]) === false) {
                        $raw[$key][] = $value;
                    }
                    $raw[$key] = array_unique($raw[$key]);
                    if (count($raw[$key]) === 1) {
                        $raw[$key] = array_shift($raw[$key]);
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
        return $raw;
    }

    public function getStatus(array $rawData)
    {
        return $rawData[$this->statusField] ?? null;
    }
}
