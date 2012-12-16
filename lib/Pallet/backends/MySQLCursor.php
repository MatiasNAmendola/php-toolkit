<?php

namespace Pallet\backends;

use \Pallet\BackendCursor;

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
