<?php

namespace DeadusefulWhoisParser\Records;

class Registrar extends Record
{
    public ?string $name;
    public ?string $abuse_contact_phone;
    public ?string $abuse_contact_email;
    public ?string $iana_id;
    public ?string $url;
    public ?string $whois_server;
}
