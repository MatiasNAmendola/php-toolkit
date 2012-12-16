<?php 

namespace Pallet;

interface BackendCursor
{
	/**
	 * Returns the next item the cursor can see.
	 */
	function next();
}