<?php

namespace Pallet\backends;

use \Pallet\Model;
use \Pallet\Fields;
use \Pallet\Backend;

class MySQLBackend implements Backend
{
	private $db;
	
	public $debug_queries = false;
	
	public $query_count = 0;
	
	function __construct($host, $user = null, $pass = null, $db = null)
	{
        if( is_object($host) )
        {
            $this->db = $host;
        }
        else
        {
		    $this->db = mysqli_connect( $host, $user, $pass, $db );
        }
		if($this->db->connect_errno)
		{
			print_r($this->db->connect_error);
		}
	}
	
	/**
	 * Executes a QuerySet and returns a MySQLCursor for the result.
	 */
	function executeQuery( $query )
	{
		$sql = $this->getQuerySQL($query);
		
		$cursor = new MySQLCursor($this->query($sql));
			
		return $cursor;
	}
	
	function save( &$model )
	{
		$pkey = $model->getPrimaryKeyField();
		if(is_null($pkey)) {
			trigger_error("Inserting Model with no Primary Key");
		}

        $is_insertion = !isset($model->{$pkey->_name});
		
		// Copy data into an intermediate object so we can reverse foreign-key relationships.
		$row = $model;
		foreach($model->getFields() as $name => $field)
		{
			if(is_object($field) && $field instanceof \Pallet\ForeignKey )
			{
				$fkey = $field->field;
				$key = $row->$name->$fkey;
				$row->$name = $key;
			}

            // Set insert-time values for unset fields.
            if($is_insertion) {
                if(!isset($row->$name) && method_exists($field, 'defaultValue'))
                {
                    $row->$name = $field->defaultValue();
                }
            }
        }
		
		if($is_insertion)
		{
			$sql = $this->getInsertionSQL($model);
			$this->query($sql);
			$model->{$pkey->_name} = $this->db->insert_id;
		}
		else
		{
			$sql = $this->getUpdateSQL($model);
			$this->query($sql);
		}
		
		return $model;
	}
	
	/**
	 * Runs a raw SQL query against the backend.
	 */
	function query($q) 
	{
		$this->query_count++;
		if( $this->debug_queries ) {
			var_dump($q);
		}
		$r = $this->db->query($q);
		if(!$r && $this->debug_queries) {
			print_r($this->db->error);
		}
		return $r;
	}
	
	/**
	 * Escapes a value for use in a Query.
	 */
	function escapeValue( $value )
	{
		if( is_string($value) )
		{
			return '\''.$this->db->real_escape_string($value).'\'';
		}
		return $value;
	}
	
	/**
	 * Returns the SQL for a select statement that represents the given QuerySet.
	 */
	function getQuerySQL( $query )
	{
		$fields = null;
		$querystr = 'SELECT ' 
			. (is_array($fields) ? implode(',', $fields) : '*') 
			. ' FROM '. $query->getModel()->_name . '';
		$conditions = $this->getConditionSQL($query);
		if( is_string($conditions) ) 
		{
			$querystr = $querystr . ' WHERE ' . $conditions;
		}
        $order = $this->getSortSQL($query);
        $skp = array();
        if( $query->_limit !== NULL ) $skp[] = 'LIMIT ' . $query->_limit;
        if( $query->_offset !== NULL ) $skp[] = 'OFFSET ' . $query->_offset;
		return "$querystr $order " . implode(' ', $skp) . ';';
	}

    /**
     * Returns the SQL for a given sorting
     */
    function getSortSQL( $query )
    {
        if( count($query->sorting) === 0 ) return '';
        $conds = array();
        foreach($query->sorting as $s)
        {
            if(substr($s, 0, 1) !== '-')
            {
                $conds[] = $s;
            }
            else
            {
                $conds[] =  substr($s, 1) . ' DESC';
            }
        }
        return 'ORDER BY ' . implode(', ', $conds);
    }
	
	/**
	 * Returns the SQL for an INSERT statement that represents the given Model.
	 */
	function getInsertionSQL( $model )
	{
		$sql = 'INSERT INTO ' . $model->_name;
		$fields = array();
		$values = array();
		foreach( $model->getFields() as $p => $v ) {
			if(isset($model->$p))
			{
				$fields[] = $p;
				$values[] = $this->escapeValue($model->$p);
			}
		}
		return $sql . ' (' . implode(', ', $fields) .') VALUES (' . implode(',', $values) . ');';
	}
	
	/**
	 * Returns the SQL for an UPDATE that represents the given Model
	 */
	 function getUpdateSQL( $model ) 
	 {
		$sql = 'UPDATE ' . $model->_name . ' SET';
		$values = array();
		foreach( $model->getFields() as $p => $v ) {
			if(isset($model->$p) && !($v instanceof \Pallet\KeyField))
			{
				$values[] = $p .'=' . $this->escapeValue($model->$p);
			}
		}
		$pkey = $model->getPrimaryKeyField();
		if(is_null($pkey)) {
			trigger_error("Cannot update a model with no primay key field");
		}
		if(!isset($model->{$pkey->_name}) ) {
			trigger_error("Cannot update a model with no primary key");
		}
		$pkey_val = $model->{$pkey->_name};
		return $sql . ' ' . implode(', ', $values) . ' WHERE '. $pkey->_name . '=' . $this->escapeValue($pkey_val) . ';';
	 }

	function evalNode($comp, $vals)
	{
		$symb = ' == ';
		switch($comp) {
			case 'eq':
			case '==':
				$symb = ' = ';
				break;
			case 'ne':
			case '!=':
				$symb = ' != ';
				break;
			case 'lt':
			case '<':
				$symb = ' < ';
				break;
			case 'gt':
			case '>':
				$symb = ' > ';
				break;
			case 'le':
			case '<=':
				$symb = ' <= ';
				break;
			case 'ge':
			case '>=':
				$symb = ' >= ';
				break;
			case 'in':
			case 'IN':
				$symb = ' IN ';
		}
		
		if( $comp === 'in' )
		{
			return $vals[0] . $symb . '('. implode(',', array_map( 'quote_input', $vals[1] ) ) .')';
		}
		else {
			return $vals[0] . $symb . (is_string($vals[1]) ? '\'' .$vals[1] . '\'' : $vals[1]);
		}
	}
	
	function getConditionSQL($query)
	{
		$conditions = $query->getConditions();
		if( count($conditions) === 0 )
		{
			return null;
		}
		$q = array();
		foreach($conditions as $c => $vs)
		{
			$q[] = $this->evalNode($c, $vs);
		}
		return implode(' AND ', $q );
	}
}
