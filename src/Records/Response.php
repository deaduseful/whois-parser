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
    public ?stdClass $dates;
    public ?string $change_hash;
    public ?string $parser_version;

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->registrant = $this->registrant;
        $properties->registration = $this->registration;
        $properties->name_servers = $this->name_servers;
        $properties->whois = $this->whois;
        $properties->record_source = $this->record_source;
        $properties->change_hash = $this->change_hash;
        $properties->parser_version = $this->parser_version;
        return $properties;
    }

    public function get(string $key): mixed
    {
        if (isset($this->registration->{$key})) {
            return $this->registration->{$key};
        }
        if (isset($this->whois->{$key})) {
            return $this->whois->{$key};
        }
        return null;
    }

    public function createdDate(): ?Carbon
    {
        $key = self::DATE_CREATED;
        return $this->getDate($key);
    }

    public function expiryDate(): ?Carbon
    {
        $key = self::DATE_EXPIRY;
        return $this->getDate($key);
    }

    public function updatedDate(): ?Carbon
    {
        $key = self::DATE_UPDATED;
        return $this->getDate($key);
    }

    protected function getDate(string $key): ?Carbon
    {
        return $this->dates->{$key} ?? null;
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

    protected function has(string $key): bool
    {
        return isset($this->registration->{$key}) || isset($this->whois->{$key});
    }

    public function __toString(): string
    {
        return json_encode($this->all());
    }
}
