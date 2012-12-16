<?php

namespace Pallet;

interface Backend
{
	/**
	 * Takes a QuerySet and returns a BackendCursor that represents the result of the given query.
	 */	
	 function executeQuery($query);
	 
	 /**
	  * Saves the given model.
	  */
	 function save(&$model);
}
