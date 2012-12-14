<?php

function quote_input($input)
{
	return "'$input'";
}

class QuerySet
{
	protected $conditions;
	
	protected $model;
	
	protected $cursor = null;
	
	function __construct($model, $new, $parent = null)
	{
		$this->model = $model;
		$this->conditions = $new;
		
		if(!is_null($parent))
		{
			$this->conditions = array_merge($parent->conditions,$this->conditions);
		}
	}
	
	function filter( $field, $cond, $vals )
	{
		return new QuerySet( $this->model, array($cond => array($field, $vals)), $this );
	}
	
	function isExecuted()
	{
		return is_null($this->cursor);
	}

	function execute($backend)
	{
		$this->cursor = $backend->executeQuery($this);
	}
	
	function next()
	{
		return $this->cursor->next();
	}
	
	function getModel()
	{
		return $this->model;
	}
	
	function getConditions()
	{
		return $this->conditions;
	}
}