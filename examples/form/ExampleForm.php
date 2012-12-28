<?php
use Perform\Inputs;
use Perform\Form;

class ExampleForm extends Perform\Form
{
    function declareFields()
    {
        $this->name = Inputs::Text();
        $this->password = Inputs::Password();
        $this->number = Inputs::Number();
        $this->email = Inputs::Email();
    }
}
