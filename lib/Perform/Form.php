<?php
namespace Perform;

abstract class Form
{
    private $fields;

    public function __construct( $data )
    {
        $this->declareFields();

        $vars = get_object_vars($this);
        foreach($vars as $k => $v)
        {
            if($v instanceof Input)
            {
                $this->fields[$k] = $v;
            }
        }

        $this->raw_data = $data;
    }

    public function isValid()
    {
        foreach($this->fields as $k => $f)
        {
            if($f->isValid($this->raw_data[$k]) === FALSE)
                return FALSE;

        }
        return TRUE;
    }
	
    public function getMarkup()
    {
        $str = "";
        foreach($this->fields as $k => $f)
        {
            $str .= $f->getMarkup();
        }
        return $str;
    }
}
