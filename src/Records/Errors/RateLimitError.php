<?php

namespace Deaduseful\WhoisParser\Records\Errors;

class RateLimitError extends Error
{
    protected const TYPE = 'rate_limited';
    protected const MESSAGE = 'Rate limited';
}

