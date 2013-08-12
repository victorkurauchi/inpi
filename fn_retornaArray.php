<?php
	
// Funчуo para retornar um Array com todas as posiчѕes em que os textos .ZIP estуo armazenados.
function strallpos($haystack,$needle,$offset = 0){ 
	$result = array(); 
	for($i = $offset; $i<strlen($haystack); $i++){ 
		$pos = strpos($haystack,$needle,$i); 
		if($pos !== FALSE){ 
			$offset =  $pos; 
			if($offset >= $i){ 
				$i = $offset; 
				$result[] = $offset; 
			} 
		} 
	} 
	return $result; 
} 
//print_r(strallpos($haystack,$needle,$pos));
	
?>