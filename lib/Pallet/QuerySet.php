<?php

namespace Pallet;

class QuerySet implements \Iterator
{
	protected $conditions;
	
	protected $model;
	
	protected $cursor = null;
	
	protected $backend;

    public $sorting = array();

    public $_offset = null;

    public $_limit = null;
	
	function __construct($model, $new, $parent = null)
	{
		$this->model = $model;
		$this->conditions = $new;
		
		if(!is_null($parent))
		{
            $this->conditions = array_merge($parent->conditions,$this->conditions);
            $this->sorting = $parent->sorting; 
		}
	}
	
	function filter( $field, $cond, $vals )
	{
		return new QuerySet( $this->model, array($cond => array($field, $vals)), $this );
	}

    function sort( $order )
    {
        $this->sorting[] = $order;
        return $this;
    }

    function limit( $limit )
    {
        $this->_limit = $limit;
        return $this;
    }

    function offset( $offset )
    {
        $this->_offset = $offset;
        return $this;
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

    function count()
    {
        return $this->backend->count($this);
    }
	
	/**
	 * Iterator methods.
	 */
	public function current()
	{
		$data = $this->cursor->current();
		if(is_null($data)) return NULL;
		$model_class = get_class($this->model); 
		$model = new $model_class($data, $this->backend);
		return $model;
	}
	
	public function key()
	{
		return $this->cursor->key();
	}
	
	function next()
	{
		$this->cursor->next();
	}
	
	function rewind()
	{
		$this->cursor->rewind();
	}
	
	public function valid()
	{
		return $this->cursor->valid();
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
