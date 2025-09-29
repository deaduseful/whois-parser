<?php

namespace DeadusefulWhoisParser\Parsers;

use Carbon\Carbon;

interface Strategy {
    public function parse();

    public function getStatus(array $rawData);

    public function getCreated(array $rawData): ?Carbon;
    public function getUpdated(array $rawData): ?Carbon;
    public function getExpiry(array $rawData): ?Carbon;
    public function getRegistrar(array $rawData): ?string;
    public function getNameservers(): ?array;

    /**
     * Generate a unique hash based on the parsed WHOIS data
     * This hash should be immutable and represent the "change state" of the record
     *
     * @param array $rawData The parsed WHOIS data
     * @return string A unique hash representing the record's content
     */
    public function getHash(array $rawData): string;
}
