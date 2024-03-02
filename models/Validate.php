<?php

namespace Models;

use Exception;

class Validate
{
    private $rules;
    private $messages;
    public $state;

    private function __construct($rules)
    {
        $this->rules = $rules;
        $this->state = new StateOperation();
        $this->messages = [];
    }
    public static function Rules($rules)
    {
        $instance = new static($rules);
        return $instance;
    }
    public function setMessages($messages)
    {
        $this->messages = $messages;
        return $this;
    }

    public function validate($entry)
    {
        foreach ($entry as $key => $value) {

            $this->state->set($key, ["value" => $value]);

            if (array_key_exists($key, $this->rules)) {
                $this->validateRule($key, $value);
            }
        }
        return $this;
    }

    private function validateRule($key, $value)
    {
        $bool = str_replace(" ", "", trim($this->rules[$key]));

        if (!$bool) return;

        $rule = array_filter(explode("|", $bool), "strlen");

        try {
            foreach ($rule as $item) {
                function_exists(trim($item)) ?: $this->$item($value);
            }
        } catch (Exception $e) {

            $ruleName = $e->getMessage();

            $this->state->add($key, ["message" => $this->messages[$ruleName] ?? $this->getMessageErrorRule($ruleName)]);

            $this->state->setOperation(false);
        }
    }

    protected function required($value)
    {
        $bool = gettype($value) == "object" ?
            ($value ?? false) : (trim($value) ?? false);

        if (!$bool) throw new Exception(__FUNCTION__);
    }
    protected function number($value)
    {
        if (!is_numeric($value)) throw new Exception(__FUNCTION__);
    }


    public function correct()
    {
        return $this->state->getOperation();
    }

    private function getMessageErrorRule($ruleName)
    {
        $message = "";
        switch ($ruleName) {
            case 'required':
                $message = "Es requerido";
                break;
            case 'number':
                $message = "Debe ser un numero";
                break;
        }
        return $message;
    }
}
