<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class RegistrarNominet extends Generic
{
    protected array $matchRateLimit = [
        'the WHOIS query quota',
    ];
    protected array $matchNotFound = [
        'This domain name has not been registered.',
    ];

    protected array $matchInvalid = [
        'Nominet is not the registry for this domain name.',
    ];
}
