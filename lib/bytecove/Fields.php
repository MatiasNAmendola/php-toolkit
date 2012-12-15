<?php

namespace bytecove;

class TextField implements Field
{
	public $length;
	
	function __construct($length)
	{
		$this->length = $length;
	}
	
	function getSQL()
	{
		return "varchar($this->length)";
	}
}

class IntegerField implements Field
{	
	public $size;
	
	function __construct($size = 4)
	{
		$this->size = $size;
	}
	
	/**
	 * returns the SQL name of the type.
	 */
	 function getSqlTypename()
	 {
	 	if( $this->size == 4 ) return 'int';
		if( $this->size == 2 ) return 'smallint';
		if( $this->size == 8 ) return 'bigint';
		if( $this->size == 1 ) return 'tinyint';
		return 'int';
	 }
	
	/**
	 * Returns the SQL that defines this field.
	 */
	function getSQL()
	{
		return $this->getSqlTypename();
	}
}

class KeyField implements Field
{
	public $is_pkey;
	
	public function __construct($pkey)
	{
		$this->is_pkey = $pkey ? true : false;
	}
	
	public function getSQL()
	{
		return "int NOT NULL AUTO_INCREMENT";
	}
	
}

class Fields
{
	/**
	 * Declares a Key field.
	 * Maps to an Integer in the MySQL backend.
	 * @param $primary True for primary keys. 
	 */
	 static function Key($primary)
	 {
	 	return new KeyField($primary);
	 }
	 
	/**
	 * Declares a text field.
	 * @param $length - maximum number of characters allowed.
	 */
	static function Text( $length )
	{
		return new TextField($length);
	}
	
	/**
	 * Declares an Integer field.
	 * @param $size - Number of bytes to use, maps to SQL types as follows:
	 * 		1 - tinyint
	 * 		2 - smallint
	 * 		4 - int 
	 * 		8 - bigint
	 */
	static function Integer( $size )
	{
		return new IntegerField( $size );
	}
} 