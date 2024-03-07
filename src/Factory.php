<?php

namespace Deaduseful\WhoisParser\src;

use Deaduseful\WhoisParser\src\Parsers\Generic;

class Factory {
    public static function create(string $lookup, string $domain = null, string $whoisServer = null) {
        if ($whoisServer === 'whois.iana.org') {
            return new Generic($lookup);
        }
        return new Generic($lookup);
    }
}
