<?php

namespace Deaduseful\WhoisParser\Records\Errors;

class ReservedError extends Error
{
    protected const TYPE = 'reserved';
    protected const MESSAGE = 'Reserved';
}

