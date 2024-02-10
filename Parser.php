<?php

namespace Deaduseful\WhoisParser;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Str;
use stdClass;

class Parser
{
    const ERROR_DOMAIN_NOT_FOUND = 'Domain not found';
    const ERROR_RATE_LIMITED = 'Rate limited';
    /**
     * @var string[] Not Found Matching
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/whois.json
     */
    const MATCH_NOT_FOUND = [
        'Domain not found',
        'No match for',
        'whois.registry.in' => 'No Data Found',
    ];

    /**
     * @var string[] Rate Limit Matching
     * @see https://github.com/cheenanet/whois-servers-list/blob/master/limit-reached-messages.json
     */
    const MATCH_RATE_LIMIT = [
        'whois.joker.com' => 'Service is temporarily not available',
        'whois.nic.ch' => 'Requests of this client are not permitted',
        'whois.networksolutions.com' => 'IP Address Has Reached Rate Limit',
        'whois.nic.es' => 'exceeded the established limit',
        'whois.godaddy.com' => 'Rate limit exceeded',
        'generic' => 'Query rate exceeded, please try again later.',
        '.org' => 'WHOIS LIMIT EXCEEDED',
        'qa' => 'BLACKLISTED: You have exceeded the query limit for your network or IP address and have been blacklisted.',
        'uk_whois2' => 'not registered.  Closing...',
        'uk' => 'the WHOIS query quota',
        'app' => 'Rate limit exceeded',
        'whois.nic.vip' => 'Number of allowed queries exceeded',
        'whois.nic.cz' => 'Your connection limit exceeded',
    ];
    private stdClass $data;
    private ?string $lookup;
    private ?Carbon $date;

    public function __construct(string $lookup, string $date = null)
    {
        $this->lookup = $lookup;
        $this->date = Carbon::parse($date);
    }

    public function getData(): stdClass
    {
        if (empty($this->data)) {
            $this->data = $this->parse();
        }
        return $this->data;
    }

    private function getRaw(?string $lookup): array
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
        return $raw;
    }

    private function parse(): stdClass
    {
        $lookup = $this->lookup;
        $data = new stdClass();
        $raw = $this->getRaw($lookup);
        if (empty($raw['notFound']) === false) {
            $data->error = self::ERROR_DOMAIN_NOT_FOUND;
            return $data;
        }
        if (isset($raw[0])) {
            foreach (self::MATCH_NOT_FOUND as $find) {
                if (str_starts_with($raw[0], $find)) {
                    $data->error = self::ERROR_DOMAIN_NOT_FOUND;
                    return $data;
                }
            }
        }
        foreach (self::MATCH_RATE_LIMIT as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                $data->error = self::ERROR_RATE_LIMITED;
                return $data;
            }
        }
        if (empty($raw['domainName'])) {
            $data->error = 'Unable to parse Domain Name';
            return $data;
        }
        $response = new stdClass();
        $response->registrant = $raw['registrantName'] ?? null;
        $domainStatus = $raw['domainStatus'] ?? null;
        $statuses = is_array($domainStatus) ? $domainStatus : [$domainStatus];
        $registration = new stdClass();
        $registration->created = isset($raw['creationDate']) ? $raw['creationDate']->format('Y-m-d') : null;
        $registration->expires = isset($raw['registrarRegistrationExpirationDate']) ? $raw['registrarRegistrationExpirationDate']->format('Y-m-d') : null;
        $registration->updated = isset($raw['updatedDate']) ? $raw['updatedDate']->format('Y-m-d') : null;
        $registration->registrar = $raw['registrar'] ?? null;
        $registration->statuses = $statuses;
        $response->registration = $registration;
        $response->name_servers = $raw['nameServer'] ?? null;
        $whois = new stdClass();
        $whois->date = $this->date->format('Y-m-d');
        $whois->record = $lookup;
        $response->whois = $whois;
        $parsedWhois = new stdClass();
        $parsedWhois->domain = $raw['domainName'] ?? null;
        $parsedWhois->created_date = isset($raw['creationDate']) ? $raw['creationDate']->toAtomString() : null;
        $parsedWhois->updated_date = isset($raw['updatedDate']) ? $raw['updatedDate']->toAtomString() : null;
        $parsedWhois->expired_date = isset($raw['registrarRegistrationExpirationDate']) ? $raw['registrarRegistrationExpirationDate']->toAtomString() : null;
        $parsedWhois->statuses = $statuses;
        $parsedWhois->name_servers = $raw['nameServer'] ?? null;
        $registrar = new stdClass();
        /**
         * 'name' => 'ENOM, INC. eNom, LLC'
         * 'abuse_contact_phone' => '+1.4259744689'
         * 'abuse_contact_email' => 'ABUSE@ENOM.COM'
         * 'iana_id' => '48'
         * 'url' => 'WWW.ENOM.COM,http://www.enomd...ns.com'
         * 'whois_server' => 'WHOIS.ENOM.COM'
         */
        $registrar->name = $raw['registrar'] ?? null;
        $registrar->abuse_contact_phone = $raw['registrarAbuseContactPhone'] ?? null;
        $registrar->abuse_contact_email = $raw['registrarAbuseContactEmail'] ?? null;
        $registrar->iana_id = $raw['registrarIANAID'] ?? null;
        $registrar->url = $raw['registrarURL'] ?? null;
        $registrar->whois_server = $raw['registrarWHOISServer'] ?? null;
        $parsedWhois->registrar = $registrar;
        /**
         * 'registrant' => stdClass Object (...)
         * 'admin' => stdClass Object (...)
         * 'tech' => stdClass Object (...)
         * 'billing' => stdClass Object (...)
         */
        $contacts = new stdClass();
        /**
         * 'name' => 'REDACTED FOR PRIVACY'
         * 'org' => 'REDACTED FOR PRIVACY'
         * 'street' => Array (...)
         * 'city' => 'REDACTED FOR PRIVACY'
         * 'state' => 'WA'
         * 'postal' => 'REDACTED FOR PRIVACY'
         * 'country' => 'US'
         * 'phone' => 'REDACTED FOR PRIVACY'
         * 'fax' => 'REDACTED FOR PRIVACY'
         * 'email' => 'https://tieredaccess.com/cont...bd3696'
         */
        $registrant = new stdClass();
        $registrant->name = $raw['registrantName'] ?? '';
        $registrant->org = $raw['registrantOrganization'] ?? '';
        $registrant->street = $raw['registrantStreet'] ?? [];
        $registrant->city = $raw['registrantCity'] ?? '';
        $registrant->state = $raw['registrantState/Province'] ?? '';
        $registrant->postal = $raw['registrantPostalCode'] ?? '';
        $registrant->country = $raw['registrantCountry'] ?? '';
        $registrant->phone = $raw['registrantPhone'] ?? '';
        $registrant->fax = $raw['registrantFax'] ?? '';
        $registrant->email = $raw['registrantEmail'] ?? '';
        $contacts->registrant = $registrant;
        $admin = new stdClass();
        $admin->name = $raw['adminName'] ?? '';
        $admin->org = $raw['adminOrganization'] ?? '';
        $admin->street = $raw['adminStreet'] ?? [];
        $admin->city = $raw['adminCity'] ?? '';
        $admin->state = $raw['adminState/Province'] ?? '';
        $admin->postal = $raw['adminPostalCode'] ?? '';
        $admin->country = $raw['adminCountry'] ?? '';
        $admin->phone = $raw['adminPhone'] ?? '';
        $admin->fax = $raw['adminFax'] ?? '';
        $admin->email = $raw['adminEmail'] ?? '';
        $contacts->admin = $admin;
        $tech = new stdClass();
        $tech->name = $raw['techName'] ?? '';
        $tech->org = $raw['techOrganization'] ?? '';
        $tech->street = $raw['techStreet'] ?? [];
        $tech->city = $raw['techCity'] ?? '';
        $tech->state = $raw['techState/Province'] ?? '';
        $tech->postal = $raw['techPostalCode'] ?? '';
        $tech->country = $raw['techCountry'] ?? '';
        $tech->phone = $raw['techPhone'] ?? '';
        $tech->fax = $raw['techFax'] ?? '';
        $tech->email = $raw['techEmail'] ?? '';
        $contacts->tech = $tech;
        $billing = new stdClass();
        $billing->name = $raw['billingName'] ?? '';
        $billing->org = $raw['billingOrganization'] ?? '';
        $billing->street = $raw['billingStreet'] ?? [];
        $billing->city = $raw['billingCity'] ?? '';
        $billing->state = $raw['billingState/Province'] ?? '';
        $billing->postal = $raw['billingPostalCode'] ?? '';
        $billing->country = $raw['billingCountry'] ?? '';
        $billing->phone = $raw['billingPhone'] ?? '';
        $billing->fax = $raw['billingFax'] ?? '';
        $billing->email = $raw['billingEmail'] ?? '';
        $contacts->billing = $billing;
        $parsedWhois->contacts = $contacts;
        /**
         * 'dnssec' => 'unsigned'
         * 'registry_domain_id' => '1697312_DOMAIN_COM-VRSN'
         */
        $otherProperties = new stdClass();
        $otherProperties->dnssec = $raw['dNSSEC'] ?? null;
        $otherProperties->registry_domain_id = $raw['registryDomainID'] ?? null;
        $parsedWhois->other_properties = $otherProperties;
        $response->parsed_whois = $parsedWhois;
        $response->record_source = $raw['domainName'] ?? null;
        $data->response = $response;
        return $data;
    }
}
