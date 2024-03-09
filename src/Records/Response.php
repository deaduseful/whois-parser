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
    public ?array $name_servers;
    public ?Whois $whois;
    public ?string $record_source;
    public ?array $raw_data;

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->registrant = $this->registrant;
        $properties->registration = $this->registration;
        $properties->name_servers = $this->name_servers;
        $properties->whois = $this->whois;
        $properties->record_source = $this->record_source;
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
