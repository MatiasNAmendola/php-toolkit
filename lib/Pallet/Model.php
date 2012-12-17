<?php

namespace Pallet;

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
	/**
	 * Foreign keys for each model.
	 */
	 private static $_foreign = array();
	 /**
	  * The current backend of this model
	  */
  	private $_backend;
	 
    function __construct($data = null, $backend = null)
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
					if($class->implementsInterface('\Pallet\Field'))
					{
						$v->_name = $p;
						if($v instanceof \Pallet\KeyField) {
							self::$_primary[$this->_name] = $v;
						}
						if($v instanceof \Pallet\ForeignKey) {
							if(!isset(self::$_foreign[$this->_name])) {
								self::$_foreign[$this->_name] = array();
							}
							self::$_foreign[$this->_name][$p] = $v;
						}
						$fields[$p] = $v;
						unset($this->$p);
					}
				}
			}
			self::$_fields[$this->_name] = $fields;
		}
		
		if(is_array($data))
		{
			$fkeys = $this->getForeignKeys();
			if(is_array($fkeys))
			{
				foreach($fkeys as $name => $field )
				{
					// Unset foreign key values on $data so that we can lazy-load them.
					$p = "_field_$name";
					$this->$p = $data[$name];
					unset($data[$name]);
				}
			}
			
			// Copy data from $data into properties.
			foreach($data as $k => $v )
			{
				$this->$k = $v;
			}
		}
		
		$this->_backend = $backend;
	}
	
	static function all()
	{
		return new QuerySet(new static(), array());
	}
	
	function __get($prop)
	{
		$fkeys = $this->getForeignKeys();
		if(array_key_exists($prop, $fkeys)) {
			$model = $fkeys[$prop]->model;
			$field = $fkeys[$prop]->field;
			$p = "_field_$prop";
			$querys = $model::all()->filter($field, 'eq', $this->$p);
			$querys->execute($this->_backend);
			$res = $querys->next();
			if(!is_null($res)) return $res;
		}
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
	
	function getForeignKeys()
	{
		if(isset(self::$_foreign[$this->_name])) {
			return self::$_foreign[$this->_name];
		}
		return NULL;
	}
	
	function getTableSQL()
	{
		$columns = array();
		foreach($this->getFields()  as $name => $field)
		{ 
			$columns[] = "$name " . $field->getSQL();
		}
		$pkey = $this->getPrimaryKeyField();
		if(!is_null($pkey))
		{
			$columns[] = "PRIMARY KEY($pkey->_name)";
		}
		if(isset(self::$_foreign[$this->_name]))
		{
			foreach(self::$_foreign[$this->_name] as $name => $field )
			{
				$columns[] = "FOREIGN KEY ($name) REFERENCES $field->model ($field->field)";
			}
		}
		$col_str = implode(', ', $columns);
		return 	"CREATE TABLE $this->_name ($col_str);";
	}
}

