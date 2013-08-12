<?php

/**
*  Extracts a ZIP archive to the specified extract path
*
*  @param string $file The ZIP archive to extract (including the path)    
*  @param string $extractPath The path to extract the ZIP archive to
*
*  @return boolean TURE if the ZIP archive is successfully extracted, FALSE if there was an errror 
*  
*/

function zip_extract($file, $extractPath) {

    $zip = new ZipArchive;
    $res = $zip->open($file);
    if ($res === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        return TRUE;		
    } else {
        return FALSE;
    }		

} // end function

/**
$extrair_zip = zip_extract("C:/AppServ/www/sis_inpi/temp/P2128.zip", "C:/AppServ/www/sis_inpi/temp/");	//UTILIZAR DEFINE		

copy('C:/AppServ/www/sis_inpi/temp/P2128.txt', 'C:/AppServ/www/sis_inpi/revista/P2128.txt'); // UTILIZAR DEFINE

$archive_file_name = $file;
	if (file_exists($archive_file_name)) {
		unlink($archive_file_name);
	}
*/

?>