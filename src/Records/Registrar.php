<?php

namespace Deaduseful\WhoisParser\Records;

use stdClass;

class Registrar extends Record
{
    public ?string $name;
    public ?string $abuse_contact_phone;
    public ?string $abuse_contact_email;
    public ?string $iana_id;
    public ?string $url;
    public ?string $whois_server;

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->name = $this->name;
        $properties->abuse_contact_phone = $this->abuse_contact_phone;
        $properties->abuse_contact_email = $this->abuse_contact_email;
        $properties->iana_id = $this->iana_id;
        $properties->url = $this->url;
        $properties->whois_server = $this->whois_server;
        return $properties;
    }
}
