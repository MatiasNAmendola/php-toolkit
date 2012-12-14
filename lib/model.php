<?php
/**
 * All of the magic happens in here.
 */

class Model 
{
	private static $_fields = array();
	 
    function __construct()
	{	
		$reflector = new ReflectionClass(get_class($this));
		$this->_name = $reflector->getName();
		
		if(!isset(self::$_fields[$this->_name]))
		{
			$fields = array();
			$this->declareFields();
			
			$properties = get_object_vars($this); 
			
			foreach ($properties as $p => $v) {
				if( is_object($v) )
				{
					$c = get_class($v);
		
					$class = new ReflectionClass($c);
					if($class->implementsInterface('Field'))
					{
						$fields[$p] = $v;
						unset($this->$p);
					}
				}
			}
			self::$_fields[$this->_name] = $fields;
		}
	}
	
	static function all()
	{
		return new QuerySet(new static(), array());
	}
	
	function getFields()
	{
		return self::$_fields[$this->_name];
	}
	
	function getTableSQL()
	{
		$query = "CREATE TABLE $this->_name ";
		$columns = array();
		foreach($this->getFields()  as $name => $field)
		{
			$columns[] = "$name " . $field->getSQL();
		}
		return $query . "(" . implode(', ', $columns) .");";
	}
}

