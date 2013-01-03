<?php

namespace Pallet;

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

class DateTimeField implements Field
{
    public $auto_now = FALSE;
	
	public function __construct($auto_now)
	{
		$this->auto_now = $auto_now ? true : false;
	}
	
	public function getSQL()
	{
		return "datetime";
	}

    public function defaultValue()
    {
        return time();
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

class ForeignKey implements Field
{
	public $model;
	public $field;
	
	public function __construct($model, $field)
	{
		$this->model = $model;
		$this->field = $field;
	}
	
	public function getSQL()
	{
		return "int";
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

    /**
     * Declares a DateTime field.
     * @param $auto_now If true, this field will automatically be set to
     * the current timestamp on insertion.
     */
    static function DateTime( $auto_now )
    {
        return new DateTimeField( $auto_now );
    }
	
	/**
	 * Declares a foreign Key
	 */
	static function ForeignKey( $model, $field )
	{
		return new ForeignKey($model, $field);
	}
} 
