<?php

use \Pallet\Model;
use \Pallet\Fields;

class User extends Model
{
	function declareFields()
	{
		$this->id    = Fields::Key(true);
		$this->email = Fields::Text(100);
        $this->messages = Fields::ForeignKeys('Message', 'user');
	}
}

class Message extends Model
{
	function declareFields()
	{
		$this->id      = Fields::Key(true);
		$this->title   = Fields::Text(100);
		$this->message = Fields::Text(1000);
		$this->ip      = Fields::Text(16);
		$this->user    = Fields::ForeignKey('User', 'id');
        $this->post_time = Fields::DateTime(true);
	}
}
