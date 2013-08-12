<?php
function fn_enviaEmail() {
// Enviando anexo em email		
			// Aqui vamos configurar o cabeçalho (header) do e-mail, formatos, remetentes, destinatários de cópias
			$headers = "MIME-Version: 1.0\n";  
			// Abaixo estabelecemos o Remetente do E-mail com o From:
			$headers.= "From: victorkurauchi@gmail.com\r\n";
			// Caso se queira especificar o e-mail de Resposta, utilizamos o Reply-To:, caso você não queira, basta excluir a linha abaixo
			$headers.= "Reply-To: francisco@trajettoria.com\r\n";
			// Se desejar enviar cópia para outro e-mail utiliza-se o Bcc:, especificando o e-mail de destino, se não quiser mandar essa cópia, basta remover a linha abaixo
			$headers.= "Bcc: victor_kurauchi@hotmail.com\r\n";
			// Nesta linha abaixo, configuramos o "limite" ou boundary em inglês que é necessário para o arquivo em anexo.
			$boundary = "XYZ-" . date("dmYis") . "-ZYX";
			// Especificamos o tipo de conteúdo do e-mail
			$headers.= "Content-type: multipart/mixed; boundary=\"$boundary\"\r\n";  
			$headers.= "$boundary\n"; 
			// Aqui abaixo, vamos colocar o corpo da mensagem, como vamos utilizar padrão HTML, teremos de utilizar tags HTML abaixo
			$corpo_mensagem = "<html>
			<head>
			   <title>Notificação de Marcas/Patendes da Revista INPI</title>
			</head>
			<body>
			<font face=\"Arial\" size=\"2\" color=\"#333333\">
			<br />
			Script Trajettoria</b><br />
			Notificação: A palavra pesquisada foi encontrada<br />
			</font>
			</body>
			</html>";			
			// Nas linhas abaixo vamos passar os parâmetros de formatação e codificação, juntamente com a inclusão do arquivo anexado no corpo da mensagem.
			$mensagem = "--$boundary\n"; 
			$mensagem.= "Content-Transfer-Encoding: 8bits\n"; 
			$mensagem.= "Content-Type: text/html; charset=\"ISO-8859-1\"\n\n";
			$mensagem.= "$corpo_mensagem\n"; 
			$mensagem.= "--$boundary\n"; 			
			$mensagem.= "--$boundary--\r\n"; 
			
			// Cconfiguramos o e-mail do destinatário
			$destinatario = "victor@trajettoria.com";
			// Definimos o assuntos do nosso e-mail
			$assunto = "Notificação de Marcas/Patentes";

			// Após ter configurado tudo que é necessário, vamos fazer o envio propriamente dito
			mail($destinatario, $assunto, $mensagem, $headers);  
		}

?>