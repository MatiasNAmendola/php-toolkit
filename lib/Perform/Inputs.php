<?php
namespace Perform;

abstract class Input {

    /**
     * Returns the HTML markup from the form input
     */ 
    public function getMarkup($classes = null)
    {
        $type = $this->getType();
        $name = 'input';
        return "<input type=\"$type\" class=\"$classes\" name=\"$name\"></input>";
    }

    /**
     * Returns the HTML 'type' for this input
     */
    public function getType()
    {
        return 'text';
    }

    /**
     * Vertifies that the current value for the input passes validation.
     */
    abstract public function isValid($data);
}

class TextInput extends Input {
    public $length;

    public function isValid($data) {
        return strlen($data) <= $length; 
    }
}

class PasswordInput extends Input {
    public $length;

    public function getType()
    {
        return 'password';
    }

    public function isValid($data) {
        return TRUE;
    }
}

class NumberInput extends Input {
    public $min;

    public $max;

    public function __construct($min, $max) {
        $this->min = $min;
        $this->max = $max;
    }

    public function getType() {
        return 'number';
    }

    public function isValid($data) {
        return TRUE;
    }
}

class EmailInput extends Input {
    public function isValid($data)
    {
        $filterd = filter_var($data, FILTER_VALIDATE_EMAIL);
        return $filtered !== FALSE;
    }

    public function getType() {
        return 'email';
    }
}

class Inputs {
    public static function Text($min = null, $max = null) {
        return new TextInput($min, $max);
    }

    public static function Password() {
        return new PasswordInput();
    }

    public static function Number($min = null, $max = null) {
        return new NumberInput($min, $max);
    }

    public static function Email() {
        return new EmailInput();
    }
}
