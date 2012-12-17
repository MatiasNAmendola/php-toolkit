<?php

namespace Pallet;

class QuerySet
{
	protected $conditions;
	
	protected $model;
	
	protected $cursor = null;
	
	protected $backend;
	
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
		$this->backend = $backend;
		$this->cursor = $backend->executeQuery($this);
	}
	
	function next()
	{
		$data = $this->cursor->next();
		if(is_null($data)) return NULL;
		$model_class = get_class($this->model); 
		$model = new $model_class($data, $this->backend);
		return $model;
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