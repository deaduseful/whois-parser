<?php

namespace Deaduseful\WhoisParser\src;

use Deaduseful\WhoisParser\src\Parsers\Factory;
use Deaduseful\WhoisParser\src\Parsers\Strategy;
use Deaduseful\WhoisParser\src\Records\Contact;
use Deaduseful\WhoisParser\src\Records\Registrar;
use Deaduseful\WhoisParser\src\Records\Registration;
use Deaduseful\WhoisParser\src\Records\Response;
use Deaduseful\WhoisParser\src\Records\Whois;
use stdClass;

class Parser
{
    protected const ERROR_NOT_FOUND = 'Not found';
    protected const ERROR_RATE_LIMITED = 'Rate limited';
    protected const ERROR_INVALID = 'Invalid query';
    protected const ERROR_RESERVED = 'Reserved';
    protected const ERROR_BAD = 'Bad response';
    protected ?Response $response = null;
    protected ?string $lookup;
    protected ?array $raw;
    protected ?Strategy $parser;
    protected ?string $source;
    protected ?string $whoisServer;
    protected ?string $error = null;

    public function __construct(string $lookup, ?string $source = null, ?string $whoisServer = null)
    {
        $this->lookup = $lookup;
        $this->source = $source;
        $this->whoisServer = $whoisServer;
    }

    public function get(): ?Response
    {
        if ($this->error !== null) {
            return null;
        }
        if ($this->hasResponse()) {
            return $this->getResponse();
        }
        return $this->parse()->getResponse();
    }

    protected function getParser(): Strategy
    {
        if (empty($this->parser)) {
            $this->parser = Factory::create($this->lookup, $this->source, $this->whoisServer);
        }
        return $this->parser;
    }

    protected function getRaw(): array
    {
        if (empty($this->raw)) {
            $parser = $this->getParser();
            $this->raw = $parser->parse();
        }
        return $this->raw;
    }

    protected function parse(): self
    {
        $this->error = $this->checkForErrors();
        if ($this->error === null) {
            $this->setResponse();
        }
        return $this;
    }

    protected function checkForErrors(): ?string
    {
        $parser = $this->getParser();
        if ($parser->isRateLimit()) {
            return self::ERROR_RATE_LIMITED;
        }
        if ($parser->isInvalid()) {
            return self::ERROR_INVALID;
        }
        if ($parser->isReserved()) {
            return self::ERROR_RESERVED;
        }
        if ($parser->isNotFound()) {
            return self::ERROR_NOT_FOUND;
        }
        if ($parser->isBad()) {
            return self::ERROR_BAD;
        }
        return null;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }

    protected function getRegistration(): Registration
    {
        $rawData = $this->getRaw();
        $parser = $this->getParser();
        $registration = new Registration();
        $registration->created = $parser->getCreated($rawData)?->toDateString();
        $registration->expiry = $parser->getExpiry($rawData)?->toDateString();
        $registration->updated = $parser->getUpdated($rawData)?->toDateString();
        $registration->registrar = $parser->getRegistrar($rawData);
        $registration->statuses = $this->getStatuses();
        return $registration;
    }

    protected function getStatuses(): array
    {
        $rawData = $this->getRaw();
        $parser = $this->getParser();
        $statues = $parser->getStatus($rawData);
        return is_array($statues) ? $statues : [$statues];
    }

    protected function getWhois(): Whois
    {
        $rawData = $this->getRaw();
        $parser = $this->getParser();
        $whois = new Whois();
        $whois->record = $this->lookup;
        $whois->domain = $rawData['domainName'] ?? null;
        $whois->created_date = $parser->getCreated($rawData)?->toIso8601String();
        $whois->updated_date = $parser->getUpdated($rawData)?->toIso8601String();
        $whois->expired_date = $parser->getExpiry($rawData)?->toIso8601String();
        $whois->statuses = $this->getStatuses();
        $whois->name_servers = $this->getNameServers();
        $registrar = $this->getRegistrar();
        $whois->registrar = $registrar;
        $contacts = $this->getContacts();
        $whois->contacts = $contacts;
        $whois->other_properties = $this->getOtherProperties();
        return $whois;
    }

    protected function getRegistrar(): Registrar
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
        $registrar->name = $this->parser->getRegistrar($rawData);
        $registrar->abuse_contact_phone = $rawData['registrarAbuseContactPhone'] ?? null;
        $registrar->abuse_contact_email = array_key_exists('registrarAbuseContactEmail', $rawData) ? strtolower($rawData['registrarAbuseContactEmail']) : null;
        $registrar->iana_id = $rawData['registrarIANAID'] ?? null;
        $registrar->url = array_key_exists('registrarURL', $rawData) ? strtolower($rawData['registrarURL']) : null;
        $registrar->whois_server = array_key_exists('registrarWHOISServer', $rawData) ? strtolower($rawData['registrarWHOISServer']) : null;
        return $registrar;
    }

    protected function getRegistrant(): Contact
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
        $registrant->name = $rawData['registrantName'] ?? '';
        $registrant->org = $rawData['registrantOrganization'] ?? '';
        $registrant->street = $rawData['registrantStreet'] ?? '';
        $registrant->city = $rawData['registrantCity'] ?? '';
        $registrant->state = $rawData['registrantState/Province'] ?? '';
        $registrant->postal = $rawData['registrantPostalCode'] ?? '';
        $registrant->country = $rawData['registrantCountry'] ?? '';
        $registrant->phone = $rawData['registrantPhone'] ?? '';
        $registrant->fax = $rawData['registrantFax'] ?? '';
        $registrant->email = $rawData['registrantEmail'] ?? '';
        return $registrant;
    }

    protected function getAdmin(): Contact
    {
        $rawData = $this->getRaw();
        $admin = new Contact();
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

    protected function getTech(): Contact
    {
        $rawData = $this->getRaw();
        $tech = new Contact();
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

    protected function getBilling(): Contact
    {
        $rawData = $this->getRaw();
        $billing = new Contact();
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

    protected function getOtherProperties(): stdClass
    {
        $rawData = $this->getRaw();
        /**
         * 'dnssec' => 'unsigned'
         * 'registry_domain_id' => '1697312_DOMAIN_COM-VRSN'
         */
        $properties = new stdClass();
        $properties->dnssec = $rawData['dNSSEC'] ?? null;
        $properties->registry_domain_id = $rawData['registryDomainID'] ?? null;
        return $properties;
    }

    protected function parseResponse(): Response
    {
        $rawData = $this->getRaw();
        $response = new Response();
        $response->registrant = $rawData['registrantName'] ?? null;
        $registration = $this->getRegistration();
        $response->registration = $registration;
        $response->name_servers = $this->getNameServers();
        $whois = $this->getWhois();
        $response->whois = $whois;
        $response->dates = $this->getDates();
        $response->raw_data = $rawData;
        $response->record_source = $this->source;
        return $response;
    }

    protected function getContacts(): stdClass
    {
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

    public function getError(): ?string
    {
        if ($this->response !== null) {
            return null;
        }
        if ($this->error === null) {
            $this->parse();
        }
        return $this->error;
    }

    protected function getNameServers(): ?array
    {
        $rawData = $this->getRaw();
        if (empty($rawData['nameServer'])) {
            return null;
        }
        $nameServers = $rawData['nameServer'];
        $nameServers = is_string($nameServers) ? [$nameServers] : $nameServers;
        $data = [];
        foreach ($nameServers as $nameServer) {
            $data[] = strtolower(trim($nameServer, '.'));
        }
        return $data;
    }

    protected function getDates(): stdClass
    {
        $rawData = $this->getRaw();
        $dates = new stdClass();
        $dates->created = $rawData['created'] ?? null;
        $dates->updated = $rawData['updated'] ?? null;
        $dates->expiry = $rawData['expiry'] ?? null;
        return $dates;
    }

    public function isNotFound(): bool
    {
        return $this->getError() === self::ERROR_NOT_FOUND;
    }

    public function hasResponse(): bool
    {
        return !empty($this->response);
    }

    protected function setResponse(): void
    {
        $this->response = $this->parseResponse();
    }
}
