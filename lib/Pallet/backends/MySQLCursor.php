<?php

namespace Pallet\backends;

use \Pallet\BackendCursor;

class MySQLCursor implements BackendCursor
{
	private $res;
	
	private $curr;
	
	private $row;
	
	function __construct($res)
	{
		$this->res = $res;
		$this->row = 0;
		$this->curr = $this->res->fetch_assoc();
	}
	
	function current()
	{
		return $this->curr;
	}
	
	function key()
	{
		return $this->row;
	}
	
	function next()
	{
		$this->curr = $this->res->fetch_assoc();
		$this->row++;
	}
	
	function rewind()
	{
		$this->row--;
		$this->res->data_seek($this->row);
	}
	
	function valid()
	{
		return $this->row < $this->res->num_rows-1;
	}
}
