<?php

@header("Content-Type: text/html; charset=utf-8");

require_once('fn_zip.php');
require_once('fn_retornaArray.php');
require_once('fn_leituraArquivo.php');
require_once('fn_enviaEmail.php');
require_once('logger.php');

	date_default_timezone_set('America/Sao_Paulo');
	$dir_from = "http://revistas.inpi.gov.br/txt/";
	$data = file_get_contents($dir_from);	
	//Nome do arquivo:
	//$arquivoLog = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/log/log.txt";
	
	/* Obtendo arquivo db.txt para realizar compara��es com arquivos baixados ou n�o. */
	$dir_db = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/db.txt";
	$db = file_get_contents($dir_db);		
	$dir_zip = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/temp/";
	$dir_revista = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/revista/";
	$haystack = $data; 
	$needle = ".zip"; 	
	$palavraDesejada = 'Trajettoria';
	
	/* Fun��o para retornar um Array com todas as posi��es em que os textos .ZIP est�o armazenados.	*/
	$arr = strallpos($haystack,$needle); 
	
	foreach($arr as $indice => $valor) {	
		/* Recupera texto de Patentes e Marcas para comparar siglas. */
		$sigla = substr($data, ($valor-6), 10);		

		/* Concatenta diret�rio tempor�rio com o nome do arquivo zip. */
		$file = $dir_zip.$sigla;
			
		/* Recupera a sigla 'P' de patentes para poder continuar o la�o */
		$comparaSigla = substr($sigla, 0, 1);	
		
		/* Se a primeira letra do texto recupera for P de Patentes, ele continua o loop at� encontrar R de Revista */
		if ($comparaSigla == 'P') {
			continue;
		} 
			else if ($comparaSigla == 'r') {
				/** Condi��o para impedir que os textos .ZIP dentro de <a href> sejam processados.			
					Esta condi��o apenas indica que o texto n�o est� no <a href>, portanto deve ser processada.	*/
				if ($indice%2 == 0) {
					continue;
				}	else {
				
				/* Recupera posi��es dos textos onde est�o as marcas/patentes com .ZIP */		
				$acessar_db = strpos($db, $sigla);		
				$siglaTxt = substr($sigla, 0,-4);					
			
				/** Esta condi��o indica que o texto est� sendo processado dentro da string que precisamos para verifica��o,
					compara com o arquivo db.txt, e se n�o houver, o download inicia. */
				if ($acessar_db === false) {
					/* Definir url do site para depois incluir no arquivo db.txt */
					$url = "http://revistas.inpi.gov.br/txt/".$sigla;
							
					/* Movendo nome do arquivo para db.txt */
					file_put_contents($dir_db, $sigla."\r\n", FILE_APPEND);
							
					/* Obtendo cnote�do do arquivo na revista, exemplo rm2128.txt */
					$content = file_get_contents($url);											
							
					/* Movendo arquivo .zip para o diret�rio tempor�rio. */
					file_put_contents($file, $content);							
							
					/* $dir_zip � o diret�rio onde o arquivo extra�do ser� alocado, para depois ser movido para /revista */							
					$extrair_zip = zip_extract($file, $dir_zip);

					
					copy($dir_zip.$siglaTxt.'.txt', $dir_revista.$siglaTxt.'.txt');	
					
					/* Se existir algum arquivo zip na pasta temp, ser� excluido */
					if (file_exists($file) && file_exists($dir_zip.$siglaTxt.'.txt')) {
						unlink($file);
						unlink($dir_zip.$siglaTxt.'.txt');
					}		
					
							
				/* Chamar fn_leituraArquivo para verificar se o nome Trajettoria consta na Revista de marcas */
				$arquivo = $dir_revista.$siglaTxt.".txt"; 								

				/* Primeiramente, verificamos se o arquivo existe, e ent�o podemos 
					chamar fn_leituraArquivo para ent�o verificar se o nome Trajettoria consta na Revista de marcas */
					if (file_exists($arquivo)) {
							if (leitura_arquivoRevista($arquivo, $palavraDesejada) !== false) {								
								echo 'A palavra foi encontrada e um email foi enviado para notificacao <br/>';
								Logger('Palava encontrada');
								return fn_enviaEmail();
							}
								else {
									Logger("Palava n�o encontrada na revista: ".$arquivo.".Tudo ok");
								}
								
						} 
							else { 
								Logger("Arquivo corrompido ou n�o encontrado na revista");										
							}
								
				} else {
					continue;
				}
			}
				
		}
	}
		Logger("Execu��o de script realizada com sucesso.");
?>