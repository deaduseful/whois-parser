<?php

namespace Deaduseful\WhoisParser\Records\Errors;

use Deaduseful\WhoisParser\Parsers\Strategy;
use Deaduseful\WhoisParser\Records\Record;
use stdClass;

abstract class Error extends Record
{
    protected const TYPE = '';
    protected const MESSAGE = '';

    public ?string $lookup;
    public ?string $recordSource;

    public function __construct(?string $lookup = null, ?string $recordSource = null)
    {
        $this->lookup = $lookup;
        $this->recordSource = $recordSource;
    }

    public function getType(): string
    {
        return static::TYPE;
    }

    public function getMessage(): string
    {
        return static::MESSAGE;
    }

    public function isNotFound(): bool
    {
        return $this instanceof NotFoundError;
    }

    public function isRateLimit(): bool
    {
        return $this instanceof RateLimitError;
    }

    public function isInvalid(): bool
    {
        return $this instanceof InvalidError;
    }

    public function isReserved(): bool
    {
        return $this instanceof ReservedError;
    }

    public function isBad(): bool
    {
        return $this instanceof BadError;
    }

    public function all(): stdClass
    {
        $properties = new stdClass();
        $properties->type = $this->getType();
        $properties->message = $this->getMessage();
        $properties->lookup = $this->lookup;
        $properties->record_source = $this->recordSource;
        return $properties;
    }

    /**
     * Factory method to create appropriate error from parser state
     */
    public static function fromParser(Strategy $parser, ?string $lookup = null, ?string $recordSource = null): ?self
    {
        if ($parser->isRateLimit()) {
            return new RateLimitError($lookup, $recordSource);
        }
        if ($parser->isInvalid()) {
            return new InvalidError($lookup, $recordSource);
        }
        if ($parser->isReserved()) {
            return new ReservedError($lookup, $recordSource);
        }
        if ($parser->isNotFound()) {
            return new NotFoundError($lookup, $recordSource);
        }
        if ($parser->isBad()) {
            return new BadError($lookup, $recordSource);
        }
        return null;
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}

