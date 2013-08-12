<?php

function leitura_arquivoRevista ($arquivo, $palavraDesejada) {

	/** $txt = file_get_contents($arquivo);
	
	if (!is_array($palavraDesejada)) {
		return stripos($txt, $palavraDesejada);
	} else {
		foreach($palavraDesejada as $valor) {
			$pos1 = stripos($txt, $valores);
				if ($pos1 !== false) return true;
		}
		return $palavraDesejada;
	} */
	
	$txt = file_get_contents($arquivo);
	if (!is_array($palavraDesejada)) {
		return stripos($txt, $palavraDesejada);
	} else {
		foreach($palavraDesejada as $valor) {
			$pos1 = stripos($txt, $valores);
				if ($pos1 !== false) return true;
		}
	}
		return false;
		
}		


?>