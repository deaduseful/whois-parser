<?php

namespace Deaduseful\WhoisParser\src;

use Carbon\Carbon;
use Deaduseful\WhoisParser\src\Records\Contact;
use Deaduseful\WhoisParser\src\Records\Other;
use Deaduseful\WhoisParser\src\Records\Registrar;
use Deaduseful\WhoisParser\src\Records\Registration;
use Deaduseful\WhoisParser\src\Records\Response;
use Deaduseful\WhoisParser\src\Records\Whois;
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
        //'whois.joker.com' => 'Service is temporarily not available',
        //'whois.nic.ch' => 'Requests of this client are not permitted',
        //'whois.networksolutions.com' => 'IP Address Has Reached Rate Limit',
        //'whois.nic.es' => 'exceeded the established limit',
        //'whois.godaddy.com' => 'Rate limit exceeded',
        'generic' => 'Query rate exceeded, please try again later.',
        //'org' => 'WHOIS LIMIT EXCEEDED',
        //'qa' => 'BLACKLISTED: You have exceeded the query limit for your network or IP address and have been blacklisted.',
        //'uk' => 'the WHOIS query quota',
        //'app' => 'Rate limit exceeded',
        //'whois.nic.vip' => 'Number of allowed queries exceeded',
        //'whois.nic.cz' => 'Your connection limit exceeded',
    ];
    private Response $response;
    private ?string $lookup;
    private ?Carbon $date;
    private ?string $error;
    private ?array $raw;
    private ?string $source;

    public function __construct(string $lookup, string $date = null, string $source = null)
    {
        $this->lookup = $lookup;
        $this->date = Carbon::parse($date);
        $this->source = $source;
    }

    public function getOutput(): ?Response
    {
        if (empty($this->output)) {
            $this->output = $this->parse();
        }
        return $this->output;
    }

    private function getRaw(): array
    {
        if (empty($this->raw)) {
            $raw = Factory::create($this->lookup);
            $this->raw = $raw->parse();
        }
        return $this->raw;
    }

    private function parse(): ?Response
    {
        if ($this->parseRateLimit()) {
            return null;
        }
        if ($this->isNotFound()) {
            return null;
        }
        $this->response = $this->parseResponse();
        return $this->response;
    }

    protected function parseRateLimit(): bool
    {
        $lookup = $this->lookup;
        foreach (self::MATCH_RATE_LIMIT as $find) {
            if (str_contains(strtolower($lookup), strtolower($find))) {
                $this->error = self::ERROR_RATE_LIMITED;
                return true;
            }
        }
        return false;
    }

    private function isNotFound(): bool
    {
        foreach (self::MATCH_NOT_FOUND as $find) {
            if (str_starts_with($this->lookup, $find)) {
                $this->error = self::ERROR_DOMAIN_NOT_FOUND;
                return true;
            }
        }
        $rawData = $this->getRaw();
        if (empty($rawData['notFound']) === false) {
            $this->error = self::ERROR_DOMAIN_NOT_FOUND;
            return true;
        }
        return false;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    private function getRegistration(): Registration
    {
        $rawData = $this->getRaw();
        $registration = new Registration();
        $registration->created = $rawData['creationDate'] ?? null;
        $registration->expires = $rawData['registrarRegistrationExpirationDate'] ?? null;
        $registration->updated = $rawData['updatedDate'] ?? null;
        $registration->registrar = $rawData['registrar'] ?? null;
        $registration->statuses = $this->getStatuses();
        return $registration;
    }

    private function getStatuses(): array
    {
        $rawData = $this->getRaw();
        $domainStatus = $rawData['domainStatus'] ?? null;
        return is_array($domainStatus) ? $domainStatus : [$domainStatus];
    }

    private function getWhois(): Whois
    {
        $rawData = $this->getRaw();
        $whois = new Whois();
        $whois->date = $this->date->format('Y-m-d');
        $whois->record = $this->lookup;
        $whois->domain = $rawData['domainName'] ?? null;
        $whois->created_date = $rawData['creationDate'] ?? null;
        $whois->updated_date = $rawData['updatedDate'] ?? null;
        $whois->expired_date = $rawData['registrarRegistrationExpirationDate'] ?? null;
        $whois->statuses = $this->getStatuses();
        $whois->name_servers = $rawData['nameServer'] ?? null;
        return $whois;
    }

    private function getRegistrar(): Registrar
    {
        $rawData = $this->getRaw();
        $registrar = new Registrar();
        /**
         * 'name' => 'ENOM, INC. eNom, LLC'
         * 'abuse_contact_phone' => '+1.4259744689'
         * 'abuse_contact_email' => 'ABUSE@ENOM.COM'
         * 'iana_id' => '48'
         * 'url' => 'WWW.ENOM.COM,http://www.enomd...ns.com'
         * 'whois_server' => 'WHOIS.ENOM.COM'
         */
        $registrar->name = $rawData['registrar'] ?? null;
        $registrar->abuse_contact_phone = $rawData['registrarAbuseContactPhone'] ?? null;
        $registrar->abuse_contact_email = $rawData['registrarAbuseContactEmail'] ?? null;
        $registrar->iana_id = $rawData['registrarIANAID'] ?? null;
        $registrar->url = $rawData['registrarURL'] ?? null;
        $registrar->whois_server = $rawData['registrarWHOISServer'] ?? null;
        return $registrar;
    }

    private function getRegistrant(): Contact
    {
        $rawData = $this->getRaw();
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
        $registrant = new Contact();
        $registrant->type = 'registrant';
        $registrant->name = $rawData['registrantName'] ?? '';
        $registrant->org = $rawData['registrantOrganization'] ?? '';
        $registrant->street = $rawData['registrantStreet'] ?? [];
        $registrant->city = $rawData['registrantCity'] ?? '';
        $registrant->state = $rawData['registrantState/Province'] ?? '';
        $registrant->postal = $rawData['registrantPostalCode'] ?? '';
        $registrant->country = $rawData['registrantCountry'] ?? '';
        $registrant->phone = $rawData['registrantPhone'] ?? '';
        $registrant->fax = $rawData['registrantFax'] ?? '';
        $registrant->email = $rawData['registrantEmail'] ?? '';
        return $registrant;
    }

    private function getAdmin(): Contact
    {
        $rawData = $this->getRaw();
        $admin = new Contact();
        $admin->type = 'admin';
        $admin->name = $rawData['adminName'] ?? '';
        $admin->org = $rawData['adminOrganization'] ?? '';
        $admin->street = $rawData['adminStreet'] ?? [];
        $admin->city = $rawData['adminCity'] ?? '';
        $admin->state = $rawData['adminState/Province'] ?? '';
        $admin->postal = $rawData['adminPostalCode'] ?? '';
        $admin->country = $rawData['adminCountry'] ?? '';
        $admin->phone = $rawData['adminPhone'] ?? '';
        $admin->fax = $rawData['adminFax'] ?? '';
        $admin->email = $rawData['adminEmail'] ?? '';
        return $admin;
    }

    private function getTech(): stdClass
    {
        $rawData = $this->getRaw();
        $tech = new stdClass();
        $tech->name = $rawData['techName'] ?? '';
        $tech->org = $rawData['techOrganization'] ?? '';
        $tech->street = $rawData['techStreet'] ?? [];
        $tech->city = $rawData['techCity'] ?? '';
        $tech->state = $rawData['techState/Province'] ?? '';
        $tech->postal = $rawData['techPostalCode'] ?? '';
        $tech->country = $rawData['techCountry'] ?? '';
        $tech->phone = $rawData['techPhone'] ?? '';
        $tech->fax = $rawData['techFax'] ?? '';
        $tech->email = $rawData['techEmail'] ?? '';
        return $tech;
    }

    private function getBilling(): Contact
    {
        $rawData = $this->getRaw();
        $billing = new Contact();
        $billing->type = 'billing';
        $billing->name = $rawData['billingName'] ?? '';
        $billing->org = $rawData['billingOrganization'] ?? '';
        $billing->street = $rawData['billingStreet'] ?? [];
        $billing->city = $rawData['billingCity'] ?? '';
        $billing->state = $rawData['billingState/Province'] ?? '';
        $billing->postal = $rawData['billingPostalCode'] ?? '';
        $billing->country = $rawData['billingCountry'] ?? '';
        $billing->phone = $rawData['billingPhone'] ?? '';
        $billing->fax = $rawData['billingFax'] ?? '';
        $billing->email = $rawData['billingEmail'] ?? '';
        return $billing;
    }

    private function getOtherProperties(): Other
    {
        $rawData = $this->getRaw();
        /**
         * 'dnssec' => 'unsigned'
         * 'registry_domain_id' => '1697312_DOMAIN_COM-VRSN'
         */
        $other = new Other();
        $other->dnssec = $rawData['dNSSEC'] ?? null;
        $other->registry_domain_id = $rawData['registryDomainID'] ?? null;
        return $other;
    }

    private function parseResponse(): Response
    {
        $rawData = $this->getRaw();
        $response = new Response();
        $response->registrant = $rawData['registrantName'] ?? null;
        $registration = $this->getRegistration();
        $response->registration = $registration;
        $response->nameServers = $rawData['nameServer'] ?? null;
        $whois = $this->getWhois();
        $response->whois = $whois;
        $registrar = $this->getRegistrar();
        $response->registrar = $registrar;
        $contacts = $this->getContacts();
        $response->contacts = $contacts;
        $otherProperties = $this->getOtherProperties();
        $response->other = $otherProperties;
        $response->recordSource = $this->source;
        return $response;
    }

    private function getContacts(): stdClass
    {
        $rawData = $this->getRaw();
        /**
         * 'registrant' => stdClass Object (...)
         * 'admin' => stdClass Object (...)
         * 'tech' => stdClass Object (...)
         * 'billing' => stdClass Object (...)
         */
        $contacts = new stdClass();
        $registrant = $this->getRegistrant();
        $contacts->registrant = $registrant;
        $admin = $this->getAdmin();
        $contacts->admin = $admin;
        $tech = $this->getTech();
        $contacts->tech = $tech;
        $billing = $this->getBilling();
        $contacts->billing = $billing;
        return $contacts;
    }
}
