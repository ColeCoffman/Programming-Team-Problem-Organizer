<?php


// This is a small library of functions that are commonly used across several model and template files


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
function sqlString($str, bool $wrapQuotes = true, int $maxLength = 250)
{
	if($maxLength<0 || $maxLength>250) $maxLength = 250;
	if(!is_string($str) || $str==='') return 'NULL';
	$str = str_replace('\\', '\\\\', $str);
	$str = str_replace('"', '\\"', $str);
	$str = substr($str,0,$maxLength);
	if(is_bool($wrapQuotes) && $wrapQuotes) $str = '"'.$str.'"';
	return $str;
}


// Sanitize strings for use with the SQL LIKE operator
function sqlStringLike($str, bool $wrapPercent = true, bool $wrapQuotes = true, int $maxLength = 250)
{
	$str = sqlString($str,false,$maxLength);
	if($str==='NULL') return $str;
	$str = str_replace('%', '\\%', $str);
	$str = str_replace('_', '\\_', $str);
	if(is_bool($wrapPercent) && $wrapPercent) $str = '%'.$str.'%';
	if(is_bool($wrapQuotes) && $wrapQuotes) $str = '"'.$str.'"';
	return $str;
}


// Sanitize dates for use in SQL Queries (format is yyyy-mm-dd)
function sqlDate($date, bool $wrapQuotes = true)
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
	if(is_bool($wrapQuotes) && $wrapQuotes) $date = '"'.$date.'"';
	return $date;
}


// Sanitize integers for use in SQL Queries
// This accepts integers (45) or string integers ("45"), and returns a regular integer
function sqlInt($num, $min=NULL, $max=NULL, $default='NULL')
{
	if(is_string($num) && is_numeric($num)) $num = (int)$num;
	if(!is_int($num)) return $default;
	if(is_int($min) && $num < $min) $num = $min;
	if(is_int($max) && $num > $max) $num = $max;
	return $num;
}