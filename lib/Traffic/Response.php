<?php
namespace Traffic;

/**
 * Handy method for responses.
 */
class Response
{
	public $template;
	public $vars;
	
	public function __construct($template, $vars)
	{
		$this->template = $template;
		$this->vars = $vars;
	}
}
