<?php

namespace Deaduseful\WhoisParser\src\Parsers;

class Gg extends Generic
{
    protected ?string $dateFormat = 'jS F Y \a\t H:i:s.u'; // eg: 03rd June 2020 at 18:06:52.856
    protected array $mapFields = [
        'registeredOn' => 'created',
    ];
    protected function getLines(?string $lookup): array
    {
        $lookup = str_replace('Registered on ', 'Registered on:', $lookup);
        return parent::getLines($lookup);
    }
}
