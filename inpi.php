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
	
	/* Obtendo arquivo db.txt para realizar comparações com arquivos baixados ou não. */
	$dir_db = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/db.txt";
	$db = file_get_contents($dir_db);		
	$dir_zip = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/temp/";
	$dir_revista = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/revista/";
	$haystack = $data; 
	$needle = ".zip"; 	
	$palavraDesejada = 'Trajettoria';
	
	/* Função para retornar um Array com todas as posições em que os textos .ZIP estão armazenados.	*/
	$arr = strallpos($haystack,$needle); 
	
	foreach($arr as $indice => $valor) {	
		/* Recupera texto de Patentes e Marcas para comparar siglas. */
		$sigla = substr($data, ($valor-6), 10);		

		/* Concatenta diretório temporário com o nome do arquivo zip. */
		$file = $dir_zip.$sigla;
			
		/* Recupera a sigla 'P' de patentes para poder continuar o laço */
		$comparaSigla = substr($sigla, 0, 1);	
		
		/* Se a primeira letra do texto recupera for P de Patentes, ele continua o loop até encontrar R de Revista */
		if ($comparaSigla == 'P') {
			continue;
		} 
			else if ($comparaSigla == 'r') {
				/** Condição para impedir que os textos .ZIP dentro de <a href> sejam processados.			
					Esta condição apenas indica que o texto não está no <a href>, portanto deve ser processada.	*/
				if ($indice%2 == 0) {
					continue;
				}	else {
				
				/* Recupera posições dos textos onde estão as marcas/patentes com .ZIP */		
				$acessar_db = strpos($db, $sigla);		
				$siglaTxt = substr($sigla, 0,-4);					
			
				/** Esta condição indica que o texto está sendo processado dentro da string que precisamos para verificação,
					compara com o arquivo db.txt, e se não houver, o download inicia. */
				if ($acessar_db === false) {
					/* Definir url do site para depois incluir no arquivo db.txt */
					$url = "http://revistas.inpi.gov.br/txt/".$sigla;
							
					/* Movendo nome do arquivo para db.txt */
					file_put_contents($dir_db, $sigla."\r\n", FILE_APPEND);
							
					/* Obtendo cnoteúdo do arquivo na revista, exemplo rm2128.txt */
					$content = file_get_contents($url);											
							
					/* Movendo arquivo .zip para o diretório temporário. */
					file_put_contents($file, $content);							
							
					/* $dir_zip é o diretório onde o arquivo extraído será alocado, para depois ser movido para /revista */							
					$extrair_zip = zip_extract($file, $dir_zip);

					
					copy($dir_zip.$siglaTxt.'.txt', $dir_revista.$siglaTxt.'.txt');	
					
					/* Se existir algum arquivo zip na pasta temp, será excluido */
					if (file_exists($file) && file_exists($dir_zip.$siglaTxt.'.txt')) {
						unlink($file);
						unlink($dir_zip.$siglaTxt.'.txt');
					}		
					
							
				/* Chamar fn_leituraArquivo para verificar se o nome Trajettoria consta na Revista de marcas */
				$arquivo = $dir_revista.$siglaTxt.".txt"; 								

				/* Primeiramente, verificamos se o arquivo existe, e então podemos 
					chamar fn_leituraArquivo para então verificar se o nome Trajettoria consta na Revista de marcas */
					if (file_exists($arquivo)) {
							if (leitura_arquivoRevista($arquivo, $palavraDesejada) !== false) {								
								echo 'A palavra foi encontrada e um email foi enviado para notificacao <br/>';
								Logger('Palava encontrada');
								return fn_enviaEmail();
							}
								else {
									Logger("Palava não encontrada na revista: ".$arquivo.".Tudo ok");
								}
								
						} 
							else { 
								Logger("Arquivo corrompido ou não encontrado na revista");										
							}
								
				} else {
					continue;
				}
			}
				
		}
	}
		Logger("Execução de script realizada com sucesso.");
?>