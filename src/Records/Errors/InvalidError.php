<?php

namespace Deaduseful\WhoisParser\Records\Errors;

class InvalidError extends Error
{
    protected const TYPE = 'invalid';
    protected const MESSAGE = 'Invalid query';
}

