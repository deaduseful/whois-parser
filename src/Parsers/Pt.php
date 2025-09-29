<?php

namespace Deaduseful\WhoisParser\Parsers;

class Pt extends Generic
{
    protected ?string $dateFormat = 'd/m/Y H:i:s'; // eg: 28/02/2023 23:59:00
    protected array $mapFields = [
        'creationDate' => 'created',
        'expirationDate' => 'expiry',
    ];

    protected array $matchNotFound = [
        ' - No Match',
    ];

    public function isNotFound(): bool
    {
        $rawData = $this->parse();
        return isset($rawData[0]) &&
            str_ends_with($rawData[0], $this->matchNotFound[0]);
    }
}
