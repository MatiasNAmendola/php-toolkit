<?php 

namespace Pallet;

interface BackendCursor extends \Iterator
{
	function current();
	
	function key();
	
	/**
	 * Returns the next item the cursor can see.
	 */
	function next();
	
	function rewind();
	
	function valid();
}