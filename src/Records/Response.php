<?php

namespace Deaduseful\WhoisParser\src\Records;

use Carbon\Carbon;
use stdClass;

class Response extends Record
{
    const DATE_CREATED = 'created';
    const DATE_EXPIRY = 'expiry';
    const DATE_UPDATED = 'updated';

    public ?string $registrant;
    public ?Registration $registration;
    public ?array $nameServers;
    public ?Whois $whois;
    public ?Registrar $registrar;
    public ?stdClass $contacts;
    public ?Other $other;
    public ?string $recordSource;
    public ?array $rawData;

    public function __construct()
    {
    }

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->registrant = $this->registrant;
        $properties->registration = $this->registration;
        $properties->name_servers = $this->nameServers;
        $properties->whois = $this->whois;
        $properties->registrar = $this->registrar;
        $properties->contacts = $this->contacts;
        $properties->other = $this->other;
        $properties->record_source = $this->recordSource;
        return $properties;
    }

    public function get(string $key): mixed
    {
        if (isset($this->registration->{$key})) {
            return $this->registration->{$key};
        }
        return null;
    }

    public function createdDate(): ?Carbon
    {
        $key = self::DATE_CREATED;
        return $this->get($key);
    }

    public function expiryDate(): ?Carbon
    {
        $key = self::DATE_EXPIRY;
        return $this->get($key);
    }

    public function updatedDate(): ?Carbon
    {
        $key = self::DATE_UPDATED;
        return $this->get($key);
    }

    public function hasStatus(string $domainStatus): bool
    {
        $allStatuses = $this->get('status') ?? [];
        return in_array($domainStatus, $allStatuses);
    }

    public function getRegistrant(): ?string
    {
        return $this->registrant;
    }
}
