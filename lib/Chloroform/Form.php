<?php
namespace Chloroform;

interface Form
{
	public function isValid();
	
	public function getMarkup();
	
	public function getData(); 
}
