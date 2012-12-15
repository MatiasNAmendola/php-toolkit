<?php 

namespace bytecove;

interface BackendCursor
{
	/**
	 * Returns the next item the cursor can see.
	 */
	function next();
}