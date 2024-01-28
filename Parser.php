<?php

namespace Deaduseful\WhoisParser;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Str;
use stdClass;

class Parser
{
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
        $raw = $this->getRaw($lookup);
        $statuses = is_array($raw['domainStatus']) ? $raw['domainStatus'] : [$raw['domainStatus']];
        $data = new stdClass();
        $response = new stdClass();
        $response->registrant = $raw['registrantName'];
        $registration = new stdClass();
        $registration->created = $raw['creationDate']->format('Y-m-d');
        $registration->expires = $raw['registrarRegistrationExpirationDate']->format('Y-m-d');
        $registration->updated = $raw['updatedDate']->format('Y-m-d');
        $registration->registrar = $raw['registrar'];
        $registration->statuses = $statuses;
        $response->registration = $registration;
        $response->name_servers = $raw['nameServer'];
        $whois = new stdClass();
        $whois->date = $this->date->format('Y-m-d');
        $whois->record = $lookup;
        $response->whois = $whois;
        $parsedWhois = new stdClass();
        $parsedWhois->domain = $raw['domainName'];
        $parsedWhois->created_date = $raw['creationDate']->toAtomString();
        $parsedWhois->updated_date = $raw['updatedDate']->toAtomString();
        $parsedWhois->expired_date = $raw['registrarRegistrationExpirationDate']->toAtomString();
        $parsedWhois->statuses = $statuses;
        $parsedWhois->name_servers = $raw['nameServer'];
        $registrar = new stdClass();
        /**
         * 'name' => 'ENOM, INC. eNom, LLC'
         * 'abuse_contact_phone' => '+1.4259744689'
         * 'abuse_contact_email' => 'ABUSE@ENOM.COM'
         * 'iana_id' => '48'
         * 'url' => 'WWW.ENOM.COM,http://www.enomd...ns.com'
         * 'whois_server' => 'WHOIS.ENOM.COM'
         */
        $registrar->name = $raw['registrar'];
        $registrar->abuse_contact_phone = $raw['registrarAbuseContactPhone'];
        $registrar->abuse_contact_email = $raw['registrarAbuseContactEmail'];
        $registrar->iana_id = $raw['registrarIANAID'];
        $registrar->url = $raw['registrarURL'];
        $registrar->whois_server = $raw['registrarWHOISServer'];
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
        $otherProperties->dnssec = $raw['dNSSEC'];
        $otherProperties->registry_domain_id = $raw['registryDomainID'];
        $parsedWhois->other_properties = $otherProperties;
        $response->parsed_whois = $parsedWhois;
        $response->record_source = $raw['domainName'];
        $data->response = $response;
        $data->raw = $raw;
        return $data;
    }
}
