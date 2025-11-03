<?php

namespace Deaduseful\WhoisParser\Records;

use stdClass;

abstract class Record
{
    /**
     * Get all properties as a stdClass object
     */
    abstract public function all(): stdClass;

    public function __set(string $name, $value)
    {
        $this->{$name} = $value;
    }

    public function __isset($name)
    {
        return isset($this->{$name});
    }

    public function __get(string $name)
    {
        if ($this->__isset($this->{$name})) {
            return $this->{$name};
        }
        return null;
    }

    public function __toString(): string
    {
        return json_encode($this->all());
    }

    public function __array(): array
    {
        return (array)$this->all();
    }
}
