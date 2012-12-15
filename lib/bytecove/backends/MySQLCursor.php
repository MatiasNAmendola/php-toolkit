<?php

namespace bytecove\backends;

class MySQLCursor implements \bytecove\BackendCursor
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
