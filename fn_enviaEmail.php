<?php
function fn_enviaEmail() {
// Enviando anexo em email		
			// Aqui vamos configurar o cabe�alho (header) do e-mail, formatos, remetentes, destinat�rios de c�pias
			$headers = "MIME-Version: 1.0\n";  
			// Abaixo estabelecemos o Remetente do E-mail com o From:
			$headers.= "From: victorkurauchi@gmail.com\r\n";
			// Caso se queira especificar o e-mail de Resposta, utilizamos o Reply-To:, caso voc� n�o queira, basta excluir a linha abaixo
			$headers.= "Reply-To: francisco@trajettoria.com\r\n";
			// Se desejar enviar c�pia para outro e-mail utiliza-se o Bcc:, especificando o e-mail de destino, se n�o quiser mandar essa c�pia, basta remover a linha abaixo
			$headers.= "Bcc: victor_kurauchi@hotmail.com\r\n";
			// Nesta linha abaixo, configuramos o "limite" ou boundary em ingl�s que � necess�rio para o arquivo em anexo.
			$boundary = "XYZ-" . date("dmYis") . "-ZYX";
			// Especificamos o tipo de conte�do do e-mail
			$headers.= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";  
			$headers.= "$boundary\n"; 
			// Aqui abaixo, vamos colocar o corpo da mensagem, como vamos utilizar padr�o HTML, teremos de utilizar tags HTML abaixo
			$corpo_mensagem = "<html>
			<head>
			   <title>Notifica��o de Marcas/Patendes da Revista INPI</title>
			</head>
			<body>
			<font face=\"Arial\" size=\"2\" color=\"#333333\">
			<br />
			Script Trajettoria</b><br />
			Notifica��o: A palavra pesquisada foi encontrada<br />
			</font>
			</body>
			</html>";			
			// Nas linhas abaixo vamos passar os par�metros de formata��o e codifica��o, juntamente com a inclus�o do arquivo anexado no corpo da mensagem.
			$mensagem = "--$boundary\n"; 
			$mensagem.= "Content-Transfer-Encoding: 8bits\n"; 
			$mensagem.= "Content-Type: text/html; charset=\"ISO-8859-1\"\n\n";
			$mensagem.= "$corpo_mensagem\n"; 
			$mensagem.= "--$boundary\n"; 			
			$mensagem.= "--$boundary--\r\n"; 
			
			// Cconfiguramos o e-mail do destinat�rio
			$destinatario = "victor@trajettoria.com";
			// Definimos o assuntos do nosso e-mail
			$assunto = "Notifica��o de Marcas/Patentes";

			// Ap�s ter configurado tudo que � necess�rio, vamos fazer o envio propriamente dito
			mail($destinatario, $assunto, $mensagem, $headers);  
		}

?>