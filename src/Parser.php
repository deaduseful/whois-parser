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
    const ERROR_NOT_FOUND = 'Not found';
    const ERROR_RATE_LIMITED = 'Rate limited';
    const ERROR_INVALID = 'Invalid query';
    private Response $response;
    private ?string $lookup;
    private ?array $raw;
    private ?Strategy $parser;
    private ?string $source;
    private ?string $whoisServer;
    private ?string $error;

    public function __construct(string $lookup, ?string $source = null, ?string $whoisServer = null)
    {
        $this->lookup = $lookup;
        $this->source = $source;
        $this->whoisServer = $whoisServer;
    }

    public function getOutput(): ?Response
    {
        if (empty($this->output)) {
            $this->output = $this->parse();
        }
        return $this->output;
    }

    private function getParser(): Strategy
    {
        if (empty($this->parser)) {
            $this->parser = Factory::create($this->lookup, $this->source, $this->whoisServer);
        }
        return $this->parser;
    }

    private function getRaw(): array
    {
        if (empty($this->raw)) {
            $parser = $this->getParser();
            $this->raw = $parser->parse();
        }
        return $this->raw;
    }

    private function parse(): ?Response
    {
        $parser = $this->getParser();
        if ($parser->isRateLimit()) {
            $this->error = self::ERROR_RATE_LIMITED;
            return null;
        }
        if ($parser->isInvalid()) {
            $this->error = self::ERROR_INVALID;
            return null;
        }
        if ($parser->isNotFound()) {
            $this->error = self::ERROR_NOT_FOUND;
            return null;
        }
        $this->response = $this->parseResponse();
        return $this->response;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    private function getRegistration(): Registration
    {
        $rawData = $this->getRaw();
        $parser = $this->getParser();
        $registration = new Registration();
        $registration->created = $parser->getCreated($rawData);
        $registration->expiry = $parser->getExpiry($rawData);
        $registration->updated = $parser->getUpdated($rawData);
        $registration->registrar = $rawData['registrar'] ?? null;
        $registration->statuses = $this->getStatuses();
        return $registration;
    }

    private function getStatuses(): array
    {
        $rawData = $this->getRaw();
        $parser = $this->getParser();
        $statues = $parser->getStatus($rawData);
        return is_array($statues) ? $statues : [$statues];
    }

    private function getWhois(): Whois
    {
        $rawData = $this->getRaw();
        $parser = $this->getParser();
        $whois = new Whois();
        $whois->record = $this->lookup;
        $whois->domain = $rawData['domainName'] ?? null;
        $whois->created_date = $parser->getCreated($rawData);
        $whois->updated_date = $parser->getUpdated($rawData);
        $whois->expired_date = $parser->getExpiry($rawData);
        $whois->statuses = $this->getStatuses();
        $whois->name_servers = $this->getNameServers($rawData);
        $registrar = $this->getRegistrar();
        $whois->registrar = $registrar;
        $contacts = $this->getContacts();
        $whois->contacts = $contacts;
        $whois->other_properties = $this->getOtherProperties();
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

    private function getOtherProperties(): stdClass
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

    private function parseResponse(): Response
    {
        $rawData = $this->getRaw();
        $response = new Response();
        $response->registrant = $rawData['registrantName'] ?? null;
        $registration = $this->getRegistration();
        $response->registration = $registration;
        $response->name_servers = $this->getNameServers($rawData);
        $whois = $this->getWhois();
        $response->whois = $whois;
        $response->raw_data = $rawData;
        $response->record_source = $this->source;
        return $response;
    }

    private function getContacts(): stdClass
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
        return $this->error;
    }

    private function getNameServers(array $rawData): ?array
    {
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
}
