<?php

namespace Deaduseful\WhoisParser\src;

use Carbon\Carbon;
use Deaduseful\WhoisParser\src\Parsers\Factory;
use Deaduseful\WhoisParser\src\Parsers\Strategy;
use Deaduseful\WhoisParser\src\Records\Contact;
use Deaduseful\WhoisParser\src\Records\Other;
use Deaduseful\WhoisParser\src\Records\Registrar;
use Deaduseful\WhoisParser\src\Records\Registration;
use Deaduseful\WhoisParser\src\Records\Response;
use Deaduseful\WhoisParser\src\Records\Whois;
use stdClass;

class Parser
{
    private Response $response;
    private ?string $lookup;
    private ?array $raw;
    private ?Strategy $parser;
    private ?string $source;
    private ?string $whoisServer;

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
        if ($parser->parseRateLimit()) {
            return null;
        }
        if ($parser->isNotFound()) {
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
        $registration = new Registration();
        $registration->created = $rawData['created'] ?? null;
        $registration->expiry = $rawData['expiry'] ?? null;
        $registration->updated = $rawData['updated'] ?? null;
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
        $whois = new Whois();
        $whois->record = $this->lookup;
        $whois->domain = $rawData['domainName'] ?? null;
        $whois->created_date = $rawData['creationDate'] ?? null;
        $whois->updated_date = $rawData['updatedDate'] ?? null;
        $whois->expired_date = $rawData['registrarRegistrationExpirationDate'] ?? null;
        $whois->statuses = $this->getStatuses();
        $whois->name_servers = $this->getNameServers($rawData);
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
        $response->nameServers = $this->getNameServers($rawData);
        $whois = $this->getWhois();
        $response->whois = $whois;
        $registrar = $this->getRegistrar();
        $response->registrar = $registrar;
        $contacts = $this->getContacts();
        $response->contacts = $contacts;
        $otherProperties = $this->getOtherProperties();
        $response->other = $otherProperties;
        $response->rawData = $rawData;
        $response->recordSource = $this->source;
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
        $parser = $this->getParser();
        return $parser->getError();
    }

    private function getNameServers(array $rawData): ?array
    {
        $nameServers = $rawData['nameServer'] ?? null;
        return is_string($nameServers) ? [$nameServers] : $nameServers;
    }
}
