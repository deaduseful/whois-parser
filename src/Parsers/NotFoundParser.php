<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class NotFoundParser
{
    protected const NOT_FOUND_WORDS = [
        'is available for registration',
        'Domain not found',
        'Domain not Found',
        'No Data Found',
        'No match for',
        'No Match for',
        'returned 0 objects',
        'No Object Found',
        'object does not exist',
        'NOT FOUND',
        'Not found',
        'not found',
        'Not Found',
        'no entries found',
        'No entries found',
        'nothing found',
        'Nothing found',
        'AVAILABLE',
        'is free',
        ': free',
        'No match',
        'not been registered',
        'not registered',
        'not known',
        'No such domain',
        'Domain unknown',
        'encuentra',
        'No information available',
        'registration status: available',
        'pk' => 'Available:',
        'whois.1api.net' => 'domain name not found',
        'whois.enterprice.net' => 'Object not found',
    ];

    protected const NO_MATCH_EXACT = [
        'africa' => 'Available',
        'be' => 'Status:	AVAILABLE',
        'bo' => 'DOMINIO NO REGISTRADO',
        'gf' => 'NO OBJECT FOUND!',
        'mx' => 'No_Se_Encontro_El_Objeto/Object_Not_Found',
        'ro' => '% No entries found for the selected source(s).',
        'lt' => 'Status:			available',
        'il' => '%ERROR:101: no entries found',
    ];

    protected function findSubject(string $line): ?string
    {
        $line = trim($line);
        foreach (self::NOT_FOUND_WORDS as $word) {
            if (str_contains($line, $word)) {
                return $line;
            }
        }
        return null;
    }

    public function getMatch(string|array $lines): string
    {
        if (is_string($lines)) {
            $lines = explode(PHP_EOL, $lines);
        }
        $match = null;
        if (count($lines) === 0) {
            $match = '<no lines>';
        } elseif (count($lines) === 1) {
            $match = $lines[0];
        } else {
            foreach ($lines as $line) {
                if (in_array(trim($line), self::NO_MATCH_EXACT)) {
                    $match = $line;
                    break;
                }
            }
            if (!$match) {
                foreach ($lines as $line) {
                    if (strlen($line) < 200) {
                        $match = $this->findSubject($line);
                    }
                    if ($match) {
                        break;
                    }
                }
            }
        }
        $match = preg_replace('/"([^"]+)"/', '"%s"', $match);
        // If the string still contains a domain name, replace it with %s
        $match = preg_replace('/[a-z0-9-]+\.[a-z]{2,}/', '%s', $match);
        return trim($match);
    }
}
