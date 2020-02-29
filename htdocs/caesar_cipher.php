<?php

function encrypt($text, $s) 
{ 
	$result = ""; 

	for ($i = 0; $i < strlen($text); $i++) 
	{ 
		// Encrypt Uppercase letters 
		if (ctype_upper($text[$i]))
		{
			$result = $result.chr((ord($text[$i]) + $s - 65) % 26 + 65);
		}
		// Encrypt Lowercase letters 
		else
		{
			$result = $result.chr((ord($text[$i]) + $s - 97) % 26 + 97);
		}
	} 

	return $result; 
}