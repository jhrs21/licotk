<?php

$version = $_GET["version"];

// Instrucciones:
// - Cambiar manualmente la versión, cuando haya una actualización.
// - Cambiar manualmente la URL de descarga, de cambiar dicha URL en el servidor.

if($version != '1.5.1'){
	echo json_encode("Hay una nueva versi�n de Lealtag. �Deseas descargarla ahora?|0|http://www.lealtag.com/descarga");
}

?>
