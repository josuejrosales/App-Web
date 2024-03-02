<?php

namespace Models;

class StateOperation
{
    private $data;
    private $operation;

    public function __construct()
    {
        $this->data = [];
        $this->operation = true;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function add($key, $value)
    {
        $this->data[$key] = array_merge($this->data[$key], $value);
    }

    public function getData()
    {
        return $this->data;
    }
    public function getOperation()
    {
        return $this->operation;
    }
    public function setOperation($operation)
    {
        $this->operation = $operation;
    }
}
