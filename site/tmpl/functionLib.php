<?php

// Safely get a property from an object.
// If $obj is not a non-null object, this returns NULL.
function objGet($obj, string $propertyName)
{
	if(!is_object($obj)) return NULL;
	if(!is_string($propertyName) || $propertyName == '') return NULL;
	return $obj->$propertyName;
}

// Safely get an index from an array.
// If $arr is not an array, or if $key is not a valid key, this returns NULL.
function arrGet($arr, string $key)
{
	if(!is_array($arr)) return NULL;
	if(!is_string($key) || !array_key_exists($key, $arr)) return NULL;
	return $arr[$key];
}

// Sanitize strings for use in SQL Queries
function sqlString($str, int $maxLength = 250)
{
	if($maxLength<0 || $maxLength>250) $maxLength = 250;
	if(!is_string($str) || $str==='') return 'NULL';
	$str = str_replace('\\', '\\\\', $str);
	$str = str_replace('"', '\\"', $str);
	$str = substr($str,0,$maxLength);
	return '"'.$str.'"';
}

// Sanitize dates for use in SQL Queries (format is yyyy-mm-dd)
function sqlDate($date)
{
	if(!is_string($date) || strlen($date) !== 10) return 'NULL';
	for($i = 0; $i < strlen($date); $i++)
	{
		// hyphens
		if($i == 4 || $i == 7)
		{
			if($date[$i]!=='-') return 'NULL';
		}
		// numeric characters (0-9)
		else
		{
			if(!is_numeric($date[$i])) return 'NULL';
		}
	}
	return '"'.$date.'"';
}

// Sanitize integers for use in SQL Queries
// This accepts integers (45) or string integers ("45"), and returns a regular integer
function sqlInt($num, $min=NULL, $max=NULL)
{
	if(is_string($num) && is_numeric($num)) $num = (int)$num;
	if(!is_int($num)) return 'NULL';
	if(is_int($min) && $num < $min) $num = $min;
	if(is_int($max) && $num > $max) $num = $max;
	return $num;
}