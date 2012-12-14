<?php

include('../lib/model.php');
include('../lib/fields.php');
include('../lib/backend.php');
include('../lib/backends/mysqlbackend.php');
include('../lib/queryset.php');


class TestModel extends Model
{
	function declareFields()
	{
		$this->name  = Fields::Text(100);
		$this->descr = Fields::Text(100);
		$this->tags  = Fields::Text(100);
		$this->likes = Fields::Integer(2);
	}
}
