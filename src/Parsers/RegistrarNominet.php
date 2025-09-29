<?php

namespace Deaduseful\WhoisParser\Parsers;

use Carbon\Carbon;

class RegistrarNominet extends Generic
{
    protected array $matchRateLimit = [
        'the WHOIS query quota',
    ];

    protected array $matchNotFound = [
        'This domain name has not been registered.',
        'No match for',
    ];

    protected array $matchInvalid = [
        'Nominet is not the registry for this domain name.',
    ];

    /**
     * Override parseDate to handle UK-specific date formats
     */
    protected function parseDate(string $dateString): ?Carbon
    {
        // Handle "before Aug-1996" case - return null as it's not a valid date
        if (str_starts_with($dateString, 'before ')) {
            return null;
        }

        // Use parent parseDate for normal date strings
        return parent::parseDate($dateString);
    }

    /**
     * Override isNotFound to handle UK-specific not found patterns
     */
    public function isNotFound(): bool
    {
        // Check for UK-specific not found patterns in the lookup text
        foreach ($this->matchNotFound as $pattern) {
            if (str_contains($this->lookup, $pattern)) {
                return true;
            }
        }

        // Fall back to parent implementation
        return parent::isNotFound();
    }

    /**
     * Parse UK WHOIS data with structured headings
     */
    public function parse(): array
    {
        $lines = explode("\n", $this->lookup);
        $data = [];
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Check for section headers
            if (preg_match('/^([^:]+):\s*$/', $line, $matches)) {
                $currentSection = strtolower(trim($matches[1]));
                continue;
            }

            // Check for key-value pairs within sections
            if (preg_match('/^([^:]+):\s*(.+)$/', $line, $matches)) {
                $key = strtolower(trim($matches[1]));
                $value = trim($matches[2]);

                // Map to standard fields
                switch ($key) {
                    case 'domain name':
                        $data['domain'] = $value;
                        break;
                    case 'registrar':
                        $data['registrar'] = $value;
                        break;
                    case 'registered on':
                        $data['registeredOn'] = $value;
                        break;
                    case 'expiry date':
                        $data['expiryDate'] = $value;
                        break;
                    case 'last updated':
                        $data['lastUpdated'] = $value;
                        break;
                    case 'registration status':
                        $data['status'] = $value;
                        break;
                    case 'name servers':
                        // Skip header, collect nameservers in following lines
                        $currentSection = 'nameservers';
                        break;
                    default:
                        $data[$key] = $value;
                        break;
                }
            } else {
                // Handle continuation lines (like nameservers, addresses)
                if ($currentSection === 'nameservers' && !empty($line)) {
                    // Extract nameserver from line (may include IP addresses)
                    $nameserver = trim(explode(' ', $line)[0]);
                    if (!empty($nameserver)) {
                        $data['nameserver'][] = $nameserver;
                    }
                } elseif (!empty($line) && !preg_match('/^[A-Z\s-]+:$/', $line)) {
                    // Add continuation data to current section
                    if ($currentSection && !isset($data[$currentSection])) {
                        $data[$currentSection] = [];
                    }
                    if ($currentSection && is_array($data[$currentSection])) {
                        $data[$currentSection][] = $line;
                    }
                }
            }
        }

        return $this->mapFields($data);
    }

    /**
     * Map UK WHOIS fields to standard fields
     */
    protected function mapFields(array $data): array
    {
        $mapped = [];

        // Map domain
        if (isset($data['domain'])) {
            $mapped['domain'] = $data['domain'];
        }

        // Map registrar
        if (isset($data['registrar'])) {
            $mapped['registrar'] = $data['registrar'];
        }

        // Map status
        if (isset($data['status'])) {
            $mapped['status'] = $data['status'];
        }

        // Map dates
        if (isset($data['registeredOn'])) {
            $mapped['created'] = $this->parseDate($data['registeredOn']);
        }
        if (isset($data['lastUpdated'])) {
            $mapped['updated'] = $this->parseDate($data['lastUpdated']);
        }
        if (isset($data['expiryDate'])) {
            $mapped['expiry'] = $this->parseDate($data['expiryDate']);
        }

        // Map nameservers
        if (isset($data['nameserver']) && is_array($data['nameserver'])) {
            $mapped['nameserver'] = $data['nameserver'];
        }

        return $mapped;
    }
}
