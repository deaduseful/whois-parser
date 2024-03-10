<?php

namespace Deaduseful\WhoisParser\src\Records;

use Carbon\Carbon;
use stdClass;

class Response extends Record
{
    const DATE_CREATED = 'created';
    const DATE_EXPIRY = 'expiry';
    const DATE_UPDATED = 'updated';
    const FIELD_STATUSES = 'statuses';

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
        if (isset($this->whois->{$key})) {
            return $this->whois->{$key};
        }
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

    public function hasStatus(string $checkStatus): bool
    {
        $allStatuses = $this->get(self::FIELD_STATUSES) ?? [];
        if (in_array($checkStatus, $allStatuses)) {
            return true;
        }
        foreach ($allStatuses as $status) {
            if (str_contains(strtolower($status), strtolower($checkStatus))) {
                return true;
            }
        }
        return false;
    }

    public function getRegistrant(): ?string
    {
        return $this->registrant;
    }
}
