<?php

namespace Deaduseful\WhoisParser;

use Deaduseful\WhoisParser\Parsers\Factory;
use Deaduseful\WhoisParser\Parsers\Strategy;
use Deaduseful\WhoisParser\Records\Contact;
use Deaduseful\WhoisParser\Records\Registrar;
use Deaduseful\WhoisParser\Records\Registration;
use Deaduseful\WhoisParser\Records\Response;
use Deaduseful\WhoisParser\Records\Whois;
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
            $this->raw = $parser->getParsed();
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

    /**
     * Helper method to safely handle values that might be arrays
     * Takes the first value if it's an array, applies strtolower only when explicitly requested
     *
     * @param null|string|array $value The value to process (could be string, array, or null)
     * @param bool $lowercase Whether to apply strtolower to the result
     * @return string|null The processed value
     */
    protected function processValue(null|string|array $value, bool $lowercase = false): ?string
    {
        if (is_array($value)) {
            $value = !empty($value) ? $value[0] : null;
        }

        if ($value !== null && $lowercase) {
            return strtolower($value);
        }

        return $value;
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
        $registrar->abuse_contact_email = $this->processValue($rawData['registrarAbuseContactEmail'] ?? null, true);
        $registrar->iana_id = $rawData['registrarIANAID'] ?? null;
        $registrar->url = $this->processValue($rawData['registrarURL'] ?? null, true);
        $registrar->whois_server = $this->processValue($rawData['registrarWHOISServer'] ?? null, true);

        return $registrar;
    }

    protected function getRegistrant(): Contact|array
    {
        $contactParser = new ContactParser($this->getRaw());
        return $contactParser->getContact('registrant');
    }

    protected function getAdmin(): Contact|array
    {
        $contactParser = new ContactParser($this->getRaw());
        return $contactParser->getContact('admin');
    }

    protected function getTech(): Contact|array
    {
        $contactParser = new ContactParser($this->getRaw());
        return $contactParser->getContact('tech');
    }

    protected function getBilling(): Contact|array
    {
        $contactParser = new ContactParser($this->getRaw());
        return $contactParser->getContact('billing');
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
        $response->change_hash = $this->getHash();
        $response->parser_version = $this->getVersion();
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
        // Name servers are meant to remain as an array, so we handle them differently
        $nameServers = is_string($nameServers) ? [$nameServers] : $nameServers;

        // Process each name server consistently
        $data = [];
        foreach ($nameServers as $nameServer) {
            if (is_string($nameServer)) {
                $data[] = strtolower(trim($nameServer, '.'));
            }
        }

        return !empty($data) ? $data : null;
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

    /**
     * Get a unique hash representing the current state of the WHOIS record
     * This hash can be used to detect changes in the record over time
     * The hash includes version information to ensure it changes when parser versions change
     *
     * @return string|null A unique hash or null if there's an error
     */
    public function getHash(): ?string
    {
        $rawData = $this->getRaw();
        return $this->getParser()->getHash($rawData);
    }

    /**
     * Get version information for this parser instance
     *
     * @return string The version
     */
    public function getVersion(): string
    {
        return Version::getVersion();
    }

    protected function setResponse(): void
    {
        $this->response = $this->parseResponse();
    }
}
