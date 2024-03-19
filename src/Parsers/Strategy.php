<?php

namespace Deaduseful\WhoisParser\src\Parsers;

use Carbon\Carbon;

interface Strategy {
    public function parse();

    public function getStatus(array $rawData);

    public function getCreated(array $rawData): ?Carbon;
    public function getUpdated(array $rawData): ?Carbon;
    public function getExpiry(array $rawData): ?Carbon;
    public function getRegistrar(array $rawData): ?string;
}
