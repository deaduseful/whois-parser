<?php

namespace Deaduseful\WhoisParser\src\Records;

use Carbon\Carbon;
use stdClass;

class Response extends Record
{
    const DATE_REGISTRATION = 'registration';
    const DATE_EXPIRATION = 'expiration';
    const DATE_UPDATED = 'updated';

    public ?string $registrant;
    public ?Registration $registration;
    public ?array $nameServers;
    public ?Whois $whois;
    public ?Registrar $registrar;
    public ?stdClass $contacts;
    public ?Other $other;
    public ?string $recordSource;

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
        return $this->registration->{$key};
    }

    public function registrationDate(): ?Carbon
    {
        return $this->getDate(self::DATE_REGISTRATION);
    }

    public function expirationDate(): ?Carbon
    {
        return $this->getDate(self::DATE_EXPIRATION);
    }

    public function updatedDate(): ?Carbon
    {
        return $this->getDate(self::DATE_UPDATED);
    }

    public function hasStatus(string $domainStatus): bool
    {
        $allStatuses = $this->get('status') ?? [];
        return in_array($domainStatus, $allStatuses);
    }

    protected function getDate(string $key): ?Carbon
    {
        $dateString = $this->get($key);
        if (empty($dateString)) {
            return null;
        }
        return Carbon::parse($dateString);
    }

    public function getRegistrant(): ?string
    {
        return $this->registrant;
    }
}
