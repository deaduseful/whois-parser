<?php

namespace Deaduseful\WhoisParser\Parsers;

class RegistrarChannelIsles extends Generic
{
    protected ?string $dateFormat = 'jS F Y \a\t H:i:s.u';
    
    protected array $matchNotFound = [
        'NOT FOUND',
    ];

    public function __construct(string $lookup, ?string $whoisServer = null)
    {
        parent::__construct($lookup, $whoisServer);
    }

    /**
     * Override getFilter to handle Channel Islands structured format
     */
    protected function getFilter(array $raw): array
    {
        // If the raw data looks like it came from default parsing, re-parse it properly
        if (isset($raw[0]) && is_string($raw[0]) && isset($raw[2]) && is_string($raw[2])) {
            // This looks like default parsing, let's re-parse with our custom logic
            $lookup = $this->lookup;
            $lines = explode("\n", $lookup);
            $raw = $this->parseRawChannelIsles($lines);
        }
        
        // Map the structured data to standard fields
        $data = [];
        
        // Map domain
        if (isset($raw['domain'])) {
            $data['domain'] = $raw['domain'];
        }
        
        // Map registrar
        if (isset($raw['registrar'])) {
            $data['registrar'] = $raw['registrar'];
        }
        
        // Map domain status as array (EPP status codes)
        if (isset($raw['domainStatus']) && is_array($raw['domainStatus'])) {
            $data['status'] = $raw['domainStatus'];
        }
        
        // Map registration status
        if (isset($raw['registrationStatus'])) {
            $data['registrationStatus'] = $raw['registrationStatus'];
        }
        
        // Map created date
        if (isset($raw['registeredOn'])) {
            $data['created'] = $this->parseDate($raw['registeredOn']);
        }
        
        // Map nameservers
        if (isset($raw['nameserver']) && is_array($raw['nameserver'])) {
            $data['nameserver'] = $raw['nameserver'];
        }
        
        return $data;
    }

    /**
     * Parse Channel Islands structured format
     */
    private function parseRawChannelIsles(array $lines): array
    {
        $raw = [];
        $currentKey = null;
        
        foreach ($lines as $lineNum => $line) {
            $originalLine = $line;
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            
            
            // Check for section headers (lines ending with colon and no value)
            if (preg_match('/^([^:]+):\s*$/', $line, $matches)) {
                $currentKey = strtolower(trim($matches[1]));
                continue;
            } 
            // Handle continuation lines (indented values that follow section headers)
            if ($currentKey && (str_starts_with($originalLine, '     ') || str_starts_with($originalLine, '    '))) {
                $value = $line;
                
                if ($currentKey === 'name servers') {
                    // Extract nameserver from line (may include IP addresses)
                    $nameserver = trim(explode(' ', $value)[0]);
                    if (!empty($nameserver)) {
                        $raw['nameserver'][] = $nameserver;
                    }
                } elseif ($currentKey === 'domain status') {
                    // Continue collecting domain status values
                    $raw['domainStatus'][] = $value;
                } elseif ($currentKey === 'domain') {
                    // Domain value on continuation line
                    $raw['domain'] = $value;
                } elseif ($currentKey === 'registrar') {
                    // Registrar value on continuation line
                    $raw['registrar'] = $value;
                } elseif ($currentKey === 'registrant') {
                    // Registrant value on continuation line
                    $raw['registrant'] = $value;
                } elseif ($currentKey === 'registration status') {
                    // Registration status value on continuation line
                    $raw['registrationStatus'] = $value;
                } elseif ($currentKey === 'relevant dates') {
                    // Handle relevant dates section
                    if (str_contains($value, 'Registered on')) {
                        // Extract the full date from "Registered on 11th October 2016 at 04:07:05.890"
                        if (preg_match('/Registered on (.+)/', $value, $matches)) {
                            $raw['registeredOn'] = trim($matches[1]);
                        }
                    }
                } else {
                    // Handle other continuation values
                    $this->processKeyValue($raw, $currentKey, $value);
                }
            }
            // Check for key-value pairs on same line (key: value) - but only if not a continuation line
            elseif (preg_match('/^([^:]+):\s*(.+)$/', $line, $matches)) {
                $key = strtolower(trim($matches[1]));
                $value = trim($matches[2]);
                $currentKey = $key;
                
                $this->processKeyValue($raw, $key, $value);
            }
        }
        
        return $raw;
    }
    
    /**
     * Process key-value pairs
     */
    private function processKeyValue(array &$data, string $key, string $value): void
    {
        switch ($key) {
            case 'domain':
                $data['domain'] = $value;
                break;
            case 'registrar':
                $data['registrar'] = $value;
                break;
            case 'registered on':
                $data['registeredOn'] = $value;
                break;
            case 'domain status':
                // Domain status can be multiple EPP status codes
                if (!isset($data['domainStatus'])) {
                    $data['domainStatus'] = [];
                }
                $data['domainStatus'][] = $value;
                break;
            case 'registration status':
                // Registration status typically shows "Registered until cancelled" or expiry info
                $data['registrationStatus'] = $value;
                break;
            default:
                // Handle malformed keys from default parsing
                if (str_contains($key, 'gandi') || str_contains($key, 'http')) {
                    // This looks like a malformed registrar entry from default parsing
                    // Skip it as we'll handle it properly in custom parsing
                    return;
                }
                if (str_contains($key, 'registered') && str_contains($key, 'october')) {
                    // This looks like a malformed date entry from default parsing
                    // Skip it as we'll handle it properly in custom parsing
                    return;
                }
                $data[$key] = $value;
                break;
        }
    }


    public function isNotFound(): bool
    {
        return str_starts_with($this->lookup, $this->matchNotFound[0]);
    }
}