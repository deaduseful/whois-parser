<?php

namespace Deaduseful\WhoisParser\src\Parsers;

interface Strategy {
    public function parse();

    public function getStatus(array $rawData);
}
