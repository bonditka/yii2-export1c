<?php

namespace bonditka\export1c\services\dto;


class Dto
{
    public $additionalProperty;

    public function __construct($data = [])
    {
        $this->setDataFromArray((array)$data);
    }

    protected function setDataFromArray($data)
    {
        if(empty($data)){
            return;
        }

        foreach (get_object_vars($this) as $name => $defaultValue) {
            $property = new \ReflectionProperty(get_class($this), $name);
            if (!$property->isPublic()) continue;

            $value = isset($data[$name]) ? $data[$name] : $defaultValue;

            //check special setter
            $methodName = 'set' . ucfirst(strtolower($name));
            if (method_exists($this, $methodName)) {
                $this->{$methodName}($value);
            } else {
                $this->$name = $value;
            }
        }
    }

    public static function createFromRequest($request)
    {
        return new self($request);
    }
}