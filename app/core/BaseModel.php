<?php

namespace App\Core;

abstract class BaseModel implements \JsonSerializable
{
    public function __get($name)
    {
        if (!property_exists($this, $name))
            $this->propertyError($name);
        return $this->$name;
    }

    public function __set($name, $value)
    {
        if (!property_exists($this, $name))
            $this->propertyError($name);
        $this->$name = $value;
    }

    public function __isset($name)
    {
        return isset($this->$name);
    }

    public function __unset($name)
    {
        unset($this->$name);
    }

    function jsonSerialize()
    {
        $array = get_object_vars($this);
        return array_combine(
            array_map(function ($el) {
                return ltrim($el, '_');
            }, array_keys($array)),
            array_map(function ($el) {
                return is_array($el) ? array_values($el) : $el;
            }, array_values($array)));
    }

    protected function propertyError(string $propertyName)
    {
        user_error('Класс "' . get_called_class() . "\" не содержит свойство $propertyName.");
    }
}