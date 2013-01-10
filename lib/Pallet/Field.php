<?php

namespace Pallet;

/**
 * Field interface (some refactoring needed).
 */
interface Field 
{
    /**
     * Helper function for generating Schema SQL.
     */
    function getSQL();

    /**
     * Returns false if the Field exists in the schema
     */
    function isVirtual();
}
