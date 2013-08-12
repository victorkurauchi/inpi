<?php

function Logger($msg){

$arquivoLog = "/var/www/vhosts/trajettoria.com/httpdocs/inpi/log/log.txt";  
//Texto a ser impresso no log:
$dataLog = date("d-n-Y");
$hora = date("H:i:s");
$ip = $_SERVER['REMOTE_ADDR']; 
$texto = "[$dataLog][$hora][$ip]> $msg \r\n";
 
file_put_contents($arquivoLog, $texto, FILE_APPEND);  
}

?>