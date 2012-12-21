<?php
namespace Perform\addons;

use Perform\Form;
use Pallet\Fields;

class PalletForm implements Form
{
	protected $model;
	
	public $fields;
	
	function __construct($model)
	{
		$this->model = $model;
	}
	
	function save($backend = null)
	{
		$data = $this->getData();
		
		foreach($data as $k => $v)
		{
			$this->model->$k = $v;
		}
		
		if(!is_null($backend))
		{
			$backend->save($model);
		}
		
		return $this->model;
	}
	
	function isValid()
	{
		// TODO: Validate input against model.
		$valid = true;
		$fields = $this->model->getFields();
		foreach($fields as $name => $field)
		{
			if((!is_array($this->fields) || array_search($name, $this->fields) !== false) && !isset($_POST[$name]))
			{
				$valid = false;
			}
		}
		return $valid;
	}
	
	function getInput($name, $field)
	{
		$type = "text";
		$val = isset($this->model->$name) ? $this->model->$name : '';
		$label = ucfirst($name);
		if($field instanceof \Pallet\TextField)
		{
			// Default settings are OK.
		}
		else
		{
			// If it's not a field type we can handle, let the form know it doesn't need to do anything.
			return NULL;
		}
		return "<label for=\"$name\">$label</label><input type=\"$type\" name=\"$name\" value=\"$val\"></input>";
	}
	
	function getMarkup()
	{
		$fields = $this->model->getFields();
		$inputs = array();
		foreach($fields as $name => $field)
		{
			if(!is_array($this->fields) || array_search($name, $this->fields) !== false)
			{
				$inpt = $this->getInput($name, $field);
				if(!is_null($inpt))
				{
					$inputs[] = $inpt;
				}
			}
		}
		$inputs[] = '<input type="submit" value="Submit"></input>';
		return '<form method="POST">'
			. implode('', $inputs) . '</form>';
	}
	
	function getData()
	{
		$fields = $this->model->getFields();
		$data = array();
		foreach($fields as $name => $field)
		{
			if((!is_array($this->fields) || array_search($name, $this->fields) !== false) && isset($_POST[$name]))
			{
				$data[$name] = $_POST[$name];
			}
		}
		return $data;
	}
}
