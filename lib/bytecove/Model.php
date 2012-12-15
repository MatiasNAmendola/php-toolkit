<?php

namespace bytecove;

/**
 * Model Class, contains Model behaviours and magic.
 */
class Model 
{
	/**
	 * Fields of each model type.
	 */
	private static $_fields = array();
	/**
	 * Primary key fields of each model.
	 */
	private static $_primary = array();
	 
    function __construct()
	{	
		$reflector = new \ReflectionClass(get_class($this));
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
		
					$class = new \ReflectionClass($c);
					if($class->implementsInterface('bytecove\Field'))
					{
						$v->_name = $p;
						if($v instanceof KeyField) {
							self::$_primary[$this->_name] = $v;
						}
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
	
	function getPrimaryKeyField()
	{
		if(isset(self::$_primary[$this->_name])) {
			return self::$_primary[$this->_name];
		}
		return NULL;
	}
	
	function getTableSQL()
	{
		$query = "CREATE TABLE $this->_name ";
		$columns = array();
		foreach($this->getFields()  as $name => $field)
		{
			$columns[] = "$name " . $field->getSQL();
		}
		$pkey = $this->getPrimaryKeyField();
		$pkey_str = (($pkey != NULL)? ', PRIMARY KEY('. ($pkey->_name) .')' : '' );
		return $query . "(" . implode(', ', $columns) . $pkey_str. ");";
	}
}

