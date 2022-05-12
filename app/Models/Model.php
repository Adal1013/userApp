<?php

namespace App\Models;

class Model extends Crud
{
    private $className;
    private $exclude = ["className", "table", "connection", "wheres", "sql", "exclude"];

    function __construct($table, $className, $properties = null)
    {
        parent::__construct($table);
        $this->className = $className;
        if (empty($properties)) {
            return;
        }
        foreach ($properties as $key => $value) {
            $this->{$key} = $value;
        }
    }

    protected function getAttributes()
    {
        $vars = get_class_vars($this->className);
        $attributes = [];
        foreach ($vars as $key => $value) {
            if (!in_array($key, $this->exclude)) {
                $attributes[] = $key;
            }
        }
        return $attributes;
    }

    protected function parsing($obj = null)
    {
        try {
            $attributes = $this->getAttributes();
            $finalModel = [];
            if ($obj == null) {
                foreach ($attributes as $index => $key) {
                    if (isset($this->{$key})) {
                        $finalModel[$key] = $this->{$key};
                    }
                }
                return $finalModel;
            }
            foreach ($attributes as $index => $key) {
                if (isset($obj[$key])) {
                    $finalModel[$key] = $obj[$key];
                }
            }
            return $finalModel;
        } catch (\Exception $ex) {
            throw new \Exception("Error en " . $this->className . ".parsing() => " . $ex->getMessage());
        }
    }

    public function fill($obj)
    {
        try {
            $attributes = $this->getAttributes();
            foreach ($attributes as $index => $key) {
                if (isset($obj[$key])) {
                    $this->{$key} = $obj[$key];
                }
            }
        } catch (\Exception $ex) {
            throw new \Exception("Error en " . $this->className . ".fill() => " . $ex->getMessage());
        }
    }

    public function insert($obj = null)
    {
        $obj = $this->parsing($obj);
        return parent::insert($obj);
    }

    public function update($obj)
    {
        $obj = $this->parsing($obj);
        return parent::update($obj);
    }

    public function __get($attributeName)
    {
        return $this->{$attributeName};
    }

    public function __set($attributeName, $value)
    {
        $this->{$attributeName} = $value;
    }
}
