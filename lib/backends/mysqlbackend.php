<?php

class MySQLCursor implements BackendCursor
{
	private $res;
	
	function __construct($res)
	{
		$this->res = $res;
	}
	
	function next()
	{
		return $this->res->fetch_assoc();
	}
}

class MySQLBackend implements Backend
{
	private $db;
	
	public $debug_queries = false;
	
	public $query_count = 0;
	
	function __construct($host, $user, $pass, $db)
	{
		$this->db = mysqli_connect( $host, $user, $pass, $db );
		if($this->db->connect_errno)
		{
			print_r($this->db->connect_error);
		}
	}
	
	function query($q) 
	{
		$this->query_count++;
		$r = $this->db->query($q);
		if(!$r && $this->debug_queries) {
			print_r($this->db->error);
		}
		return $this->db->query($q);
	}
	
	function escapeValue( $value )
	{
		if( is_string($value) )
		{
			return '\''.$this->db->real_escape_string($value).'\'';
		}
		return $value;
	}
	
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
		return $querystr .';';
	}
	
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

	function evalNode($comp, $vals)
	{
		$symb = ' == ';
		switch($comp) {
			case 'eq':
			case '==':
				$symb = ' == ';
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

	function executeQuery( $query )
	{
		$sql = $this->getQuerySQL($query);
		
		if( $this->debug_queries ) {
			var_dump($sql);
		}
		
		$cursor = new MySQLCursor($this->query($sql));
			
		return $cursor;
	}
	
	function save( $model )
	{
		$sql = $this->getInsertionSQL($model);
		if( $this->debug_queries ) {
			print_r($sql);
		}
		$this->query($sql);
		return $model;
	}
}
