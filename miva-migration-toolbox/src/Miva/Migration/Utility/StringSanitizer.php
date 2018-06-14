<?php

namespace Miva\Migration\Utility;

/**
*
*
*
*/
class StringSanitizer
{

	private static $defaultRemove = array("\n","\t","\r","\\u00a0","\xa0","\u00a0");

	/**
	* clean
	*
	* @param string $string
	* @param array $addToRemove - Add additional character(s) to remove passed as an array of values to replace with null.
	*/
	public static function clean($string, array $addToRemove = array())
	{
        return trim(preg_replace(
        	array(
        		'/[^(\x20-\x7F)]*/', // Funky Chars
        		'/\s{2,}/' // Multiple Spaces to 1 space
        	),
        	array('', ' '), 
        	str_replace(array_merge($addToRemove, static::$defaultRemove), ' ', $string) // user defined characters and default characters
        ));
 	}

}