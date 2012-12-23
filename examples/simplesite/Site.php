<?php

use Traffic\Response;

class Site
{
	public function index()
	{
		$resp = new Response('index.php', 
		[
			'top' => ['Barry white christamas album'],
			'recent' => ['Skrillex #1 hits']
		]);
		return $resp;
	}
}
