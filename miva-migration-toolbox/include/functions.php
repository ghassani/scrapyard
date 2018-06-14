<?php

function sanitize($value, $addToRemove = array())
{
	if(!is_array($addToRemove)){
		$addToRemove = array();
	}
    $baseRemove = array("\n","\t","\r","\\u00a0","\xa0","\u00a0");
    $_value = str_replace(array_merge($addToRemove,$baseRemove),' ',$value);
    $_value = preg_replace('/[^(\x20-\x7F)]*/','', $_value);
    $_value = preg_replace('/\s{2,}/', ' ',$_value);

    return trim($_value);
}


function codeify($string)
{
	$return = str_replace(array(' ','"','&',"'"), array('-','in','and',''), strtolower($string));
	return preg_replace(array('/\-{2,}/','/^\-/','/\-$/'), array('-','',''), $return);
}

function split_name($string)
{
	$parts = explode(' ', $string);
	if(count($parts) == 1){
		return array('fname' => $string, 'lname' => null);
	}

	if(count($parts) == 2){
		return array('fname' => $parts[0], 'lname' => $parts[1]);
	}

	$fname = $parts[0];
	unset($parts[0]);
	return array('fname' => $fname, 'lname' => implode(' ', $parts));	
}

function miva_structure_serialize(array $array, $index = null)
{
	$_index = $index;
	$returnSegments = array();
	foreach($array as $key => $value){
		if(is_array($value)){
			$_index++;
			$returnSegments[] = miva_structure_serialize($value, $_index);
		} else {
			$returnSegments[] = (!is_null($index) && is_numeric($index) ? '['.$index.']' : null).':'.$key.'='.htmlentities($value);
		}
	}
	return implode(',', $returnSegments);
}

/**
*
*/
function miva_structure_deserialize($serializedData)
{
	$segments = explode(',', $serializedData);
	$return = array();
	foreach($segments as $segment){
		$segmentSegments = explode('=', $segment);
		$return[preg_replace('/^:/', '', $segmentSegments[0])] = $segmentSegments[1];
	}
	return $return;
}