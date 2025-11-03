<?php

namespace Deaduseful\WhoisParser\Records\Errors;

class NotFoundError extends Error
{
    protected const TYPE = 'not_found';
    protected const MESSAGE = 'Not found';
}

