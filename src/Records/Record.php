<?php

namespace DeadusefulWhoisParser\Records;

abstract class Record
{
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
}
