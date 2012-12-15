<?php

use \bytecove\Model;
use \bytecove\Fields;

class TestModel extends Model
{
	function declareFields()
	{
		$this->id    = Fields::Key(true);
		$this->name  = Fields::Text(100);
		$this->descr = Fields::Text(100);
		$this->tags  = Fields::Text(100);
		$this->likes = Fields::Integer(2);
	}
}
