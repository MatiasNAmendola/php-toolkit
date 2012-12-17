<?php

use \Pallet\Model;
use \Pallet\Fields;

class Message extends Model
{
	function declareFields()
	{
		$this->id      = Fields::Key(true);
		$this->title   = Fields::Text(100);
		$this->message = Fields::Text(1000);
		$this->ip      = Fields::Text(16);
	}
}
