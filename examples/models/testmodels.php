<?php

use \bytecove\Model;
use \bytecove\Fields;

class User extends Model
{
	function declareFields()
	{
		$this->id    = Fields::Key(true);
		$this->name  = Fields::Text(100);
		$this->email = Fields::Text(100);
	}
}

class Message extends Model
{
	function declareFields()
	{
		$this->id      = Fields::Key(true);
		$this->title   = Fields::Text(100);
		$this->message = Fields::Text(1000);
		$this->sender  = Fields::ForeignKey('User', 'id');
		$this->reciver = Fields::ForeignKey('User', 'id');
	}
}
