<?php

namespace Deaduseful\WhoisParser;

use Deaduseful\WhoisParser\Records\Contact;

class ContactParser
{
    /**
     * Property mappings for contact fields
     */
    private const CONTACT_PROPERTIES = [
        'name' => 'Name',
        'org' => 'Organization',
        'city' => 'City',
        'state' => 'State/Province',
        'postal' => 'PostalCode',
        'country' => 'Country',
        'phone' => 'Phone',
        'fax' => 'Fax',
        'email' => 'Email'
    ];

    private array $rawData;

    public function __construct(array $rawData)
    {
        $this->rawData = $rawData;
    }

    /**
     * Get contact information based on prefix (registrant, admin, tech, billing)
     * Handles both single contact and multiple contacts cases
     *
     * @param string $prefix The contact type prefix (e.g., 'registrant', 'admin', 'tech', 'billing')
     * @return Contact|array Single contact or array of contacts
     */
    public function getContact(string $prefix): Contact|array
    {
        $nameKey = $prefix . 'Name';

        // Check if we have multiple contacts (name field is an array)
        if (isset($this->rawData[$nameKey]) && is_array($this->rawData[$nameKey])) {
            return $this->getMultipleContacts($prefix);
        }

        // Single contact case
        return $this->getSingleContact($prefix);
    }

    /**
     * Process multiple contacts for a given prefix
     *
     * @param string $prefix The contact type prefix
     * @return array Array of Contact objects
     */
    private function getMultipleContacts(string $prefix): array
    {
        $nameKey = $prefix . 'Name';
        $contacts = [];
        $count = count($this->rawData[$nameKey]);

        for ($i = 0; $i < $count; $i++) {
            $contact = new Contact();

            // Handle standard properties
            foreach (self::CONTACT_PROPERTIES as $property => $keySuffix) {
                $key = $prefix . $keySuffix;

                if ($property === 'email') {
                    $contact->$property = isset($this->rawData[$key]) && is_array($this->rawData[$key])
                        ? ($this->rawData[$key][$i] ?? '') : $this->processValue($this->rawData[$key] ?? '');
                } else {
                    $contact->$property = isset($this->rawData[$key]) && is_array($this->rawData[$key])
                        ? ($this->rawData[$key][$i] ?? '') : $this->rawData[$key] ?? '';
                }
            }

            // Handle street separately as it has special handling
            $streetKey = $prefix . 'Street';
            $contact->street = $this->processStreet($streetKey, $i);

            $contacts[] = $contact;
        }

        return $contacts;
    }

    /**
     * Process a single contact for a given prefix
     * Ensures only scalar values are assigned to Contact string properties (e.g. when
     * raw data contains arrays due to concatenated WHOIS records).
     *
     * @param string $prefix The contact type prefix
     * @return Contact A single Contact object
     */
    private function getSingleContact(string $prefix): Contact
    {
        $contact = new Contact();

        // Handle standard properties – always coerce to scalar so ?string never receives an array
        foreach (self::CONTACT_PROPERTIES as $property => $keySuffix) {
            $key = $prefix . $keySuffix;
            $value = $this->rawData[$key] ?? '';
            $contact->$property = $property === 'email'
                ? $this->processValue($value)
                : $this->toScalarString($value);
        }

        // Handle street separately as it has special handling
        $streetKey = $prefix . 'Street';
        $contact->street = $this->processValue($this->rawData[$streetKey] ?? '');

        return $contact;
    }

    /**
     * Process street information handling its special structure
     *
     * @param string $streetKey The key for the street data
     * @param int|null $index Optional index for multiple contacts
     * @return string The processed street value
     */
    private function processStreet(string $streetKey, ?int $index = null): string
    {
        if (!isset($this->rawData[$streetKey])) {
            return '';
        }
        if (is_array($this->rawData[$streetKey])) {
            if ($index !== null) {
                if (isset($this->rawData[$streetKey][$index]) && is_array($this->rawData[$streetKey][$index])) {
                    // Multi-line street for this specific contact
                    return $this->rawData[$streetKey][$index][0] ?? '';
                } else {
                    // Array of streets for different contacts
                    return $this->rawData[$streetKey][$index] ?? '';
                }
            } else {
                // Single contact with multi-line street
                return $this->processValue($this->rawData[$streetKey]);
            }
        }

        // Simple string street
        return $this->rawData[$streetKey];
    }

    /**
     * Helper method to safely handle values that might be arrays
     * Takes the first value if it's an array (recursively so nested arrays become scalar)
     *
     * @param null|string|array $value The value to process (could be string, array, or null)
     * @return string|null The processed value
     */
    private function processValue(null|string|array $value): ?string
    {
        while (is_array($value)) {
            $value = !empty($value) ? $value[0] : null;
        }
        return $value;
    }

    /**
     * Coerce a value to a string for Contact properties typed as ?string.
     * Prevents TypeError when raw data has duplicate keys (e.g. concatenated WHOIS).
     *
     * @param null|string|array $value Value from raw data
     * @return string|null Scalar string or null, never an array
     */
    private function toScalarString(null|string|array $value): ?string
    {
        $scalar = $this->processValue($value);
        return $scalar === null ? null : (string) $scalar;
    }
}
