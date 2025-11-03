<?php

namespace Deaduseful\WhoisParser\Records\Errors;

class BadError extends Error
{
    protected const TYPE = 'bad';
    protected const MESSAGE = 'Bad response';
}

