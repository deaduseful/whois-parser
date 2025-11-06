<?php

namespace Deaduseful\WhoisParser\Parsers;

/**
 * SGNIC - Singapore Network Information Centre
 * 
 * Handles .sg TLD and related Singapore domains
 */
class SgNic extends Generic
{
    protected array $matchNotFound = [
        'Domain Not Found',
    ];
    
    protected array $matchReserved = [
        'Error code: 01044',
        'usage restrictions applied',
        'Reserved by QDR',
    ];
    
    protected array $matchInvalid = [
        'error 350',
        'Invalid Query Syntax',
        'Error code: 02304',
        'Erreur	Syntax',
    ];

    protected array $mapFields = [
        'creationDate' => 'created',
        'modifiedDate' => 'updated',
        'expirationDate' => 'expiry',
    ];
}

