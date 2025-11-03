<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Deaduseful\WhoisParser\Version;

abstract class KeyValue implements Strategy
{
    public const DATE_CREATED = 'created';
    public const DATE_UPDATED = 'updated';
    public const DATE_EXPIRY = 'expiry';
    protected ?string $dateFormat = null;
    protected array $matchNotFound = [
        'No Object Found',
    ];
    protected array $matchRateLimit = [];
    protected array $matchInvalid = [];
    
    /**
     * Status patterns that indicate domain is NOT transferable
     * Checked against the status field
     */
    protected array $matchNonTransferable = [
        'clientTransferProhibited',
        'serverTransferProhibited',
        'clientUpdateProhibited',
        'serverUpdateProhibited',
        'pendingDelete',
        'pendingTransfer',
        'redemptionPeriod',
    ];
    
    /**
     * Status patterns that indicate domain IS transferable
     * Only used if no non-transferable patterns match
     */
    protected array $matchTransferable = [
        'ok',
    ];

    protected ?string $lookup;
    protected ?string $error = null;
    protected array $dateFields = [
        self::DATE_CREATED,
        self::DATE_UPDATED,
        self::DATE_EXPIRY,
    ];
    protected array $nullDateStrings = [
        'Never',
        'UNDEFINED',
        'N/A',
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
    protected string $statusField = 'status';
    protected string $registrarField = 'registrar';
    protected ?array $parsed = null;
    protected ?string $whoisServer;
    protected array $matchReserved = [];

    public function __construct(string $lookup, ?string $whoisServer = null)
    {
        $this->lookup = $lookup;
        $this->whoisServer = $whoisServer;
    }

    public function getParsed(): array
    {
        if ($this->parsed === null) {
            $this->setParsed($this->parse());
        }
        return $this->parsed;
    }

    public function setParsed(array $parsed): void
    {
        $this->parsed = $parsed;
    }

    public function getWhoisServer(): ?string
    {
        return $this->whoisServer;
    }

    public function parse(): array
    {
        $lookup = $this->getLookup();
        $lines = $this->parseLines($lookup);
        $raw = $this->parseRaw($lines);
        $data = $this->mapParsed($raw);
        return $this->getFilter($data);
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
        $lookup = $this->getLookup();
        foreach ($this->matchRateLimit as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                return true;
            }
        }
        return false;
    }

    public function isInvalid(): bool
    {
        $lookup = $this->getLookup();
        foreach ($this->matchInvalid as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                return true;
            }
        }
        return false;
    }

    public function isReserved(): bool
    {
        $lookup = $this->getLookup();
        foreach ($this->matchReserved as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                return true;
            }
        }
        return false;
    }

    public function isNotFound(): bool
    {
        $rawData = $this->getParsed();
        if (
            isset($this->matchNotFound[0]) &&
            isset($rawData[$this->statusField]) &&
            $rawData[$this->statusField] === $this->matchNotFound[0]
        ) {
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
        $dateString = trim($dateString);
        if (strlen($dateString) < 3) {
            return null;
        }
        if (in_array($dateString, $this->nullDateStrings, true)) {
            return null;
        }
        if ($dateString[0] === '-') { // eg: -0001-29-11T19:03:58Z
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

    protected function parseLines(?string $lookup): array
    {
        if (empty($lookup) === true) {
            return [];
        }
        return explode(PHP_EOL, $lookup);
    }

    private function parseRaw(array $lines): array
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
                    if (
                        $value &&
                        in_array($value, $raw[$key]) === false
                    ) {
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

    private function mapParsed(array $raw): array
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
        return $data;
    }

    public function getRegistrar(array $rawData): ?string
    {
        $registrar = $rawData[$this->registrarField] ?? null;
        if (is_array($registrar)) {
            return array_shift($registrar);
        }
        return $registrar;
    }

    public function getNameservers(): ?array
    {
        $rawData = $this->getParsed();
        $nameservers = $rawData['nameserver'] ?? null;
        if (is_array($nameservers) === false) {
            return null;
        }
        return $nameservers;
    }

    private function getLookup(): ?string
    {
        return $this->lookup;
    }

    public function isBad(): ?string
    {
        return strlen($this->getLookup()) <= 7;
    }

    /**
     * Generate a unique hash based on the parsed WHOIS data
     * This hash represents the "change state" of the record and excludes volatile timestamps
     * The hash includes version information as a salt to ensure changes when versions change
     *
     * @param  array $rawData The parsed WHOIS data
     * @return string A unique hash representing the record's content
     */
    public function getHash(array $rawData): string
    {
        // Create a data structure with only the immutable/relevant fields
        $hashData = [
            'domain' => $rawData['domainName'] ?? $rawData['domain'] ?? null,
            'registrar' => $this->getRegistrar($rawData),
            'status' => $this->getStatus($rawData),
            'nameservers' => $this->getNameservers(),
            'dnssec' => $rawData['dnssec'] ?? null,
            'registry_domain_id' => $rawData['registryDomainID'] ?? $rawData['registry_domain_id'] ?? null,
        ];

        // Add contact information if available (excluding personal details for privacy)
        $contactFields = [
            'registrantName', 'registrantOrganization', 'registrantCountry',
            'adminName', 'adminOrganization', 'adminCountry',
            'techName', 'techOrganization', 'techCountry',
            'billingName', 'billingOrganization', 'billingCountry'
        ];

        foreach ($contactFields as $field) {
            if (isset($rawData[$field])) {
                $hashData[$field] = $rawData[$field];
            }
        }

        // Filter out null/empty values and sort for consistency
        $hashData = array_filter($hashData, function ($value) {
            return $value !== null && $value !== '' && $value !== [];
        });

        // Sort keys for consistent hashing
        ksort($hashData);

        // Convert to JSON and hash
        $jsonString = json_encode($hashData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        // Include version information as salt to ensure hash changes when versions change
        $versionSalt = Version::getVersion();

        // Use SHA256 for good uniqueness and collision resistance
        return hash('sha256', $jsonString . '|' . $versionSalt);
    }

    /**
     * Determine if domain is transferable based on parsed data
     * Checks status field against non-transferable and transferable patterns
     *
     * @param  array $rawData The parsed WHOIS data
     * @return bool|null True if transferable, false if not, null if cannot determine
     */
    public function isTransferable(array $rawData): ?bool
    {
        // Get status using the configured status field
        // (domainStatus is already mapped to status via $mapFields)
        $status = $this->getStatus($rawData);
        
        // Normalize status to array
        $statuses = is_array($status) ? $status : [$status];
        
        // First check for non-transferable patterns (these take priority)
        foreach ($statuses as $statusValue) {
            if ($statusValue === null || $statusValue === '') {
                continue;
            }
            
            $statusLower = strtolower($statusValue);
            foreach ($this->matchNonTransferable as $pattern) {
                if (str_contains($statusLower, strtolower($pattern))) {
                    return false;
                }
            }
        }
        
        // Then check for transferable patterns
        foreach ($statuses as $statusValue) {
            if ($statusValue === null || $statusValue === '') {
                continue;
            }
            
            $statusLower = strtolower($statusValue);
            foreach ($this->matchTransferable as $pattern) {
                if (str_contains($statusLower, strtolower($pattern))) {
                    return true;
                }
            }
        }
        
        // Check if we have a registrar IANA ID (positive indicator when no prohibitions)
        if (!empty($rawData['registrarIANAID'])) {
            return true;
        }
        
        // Cannot determine
        return null;
    }
}
