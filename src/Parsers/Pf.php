<?php

namespace DeadusefulWhoisParser\Parsers;

class Pf extends Generic
{
    protected array $matchNotFound = [
        'Domain unknown',
    ];
    protected ?string $dateFormat = 'd/m/Y|'; // Failed to parse time string (27/05/2022) at position 0 (2): Unexpected character

    protected array $mapFields = [
        'created' => 'created',
        'lastRenewed' => 'updated',
        'expire' => 'expiry',
    ];

    protected function parseLines(?string $lookup): array
    {
        $lookup = str_replace('(JJ/MM/AAAA)', '', $lookup);
        return parent::parseLines($lookup);
    }
}
