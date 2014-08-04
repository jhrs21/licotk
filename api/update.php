<?php

$version = $_GET["version"];

// Instrucciones:
// - Cambiar manualmente la versiÃ³n, cuando haya una actualizaciÃ³n.
// - Cambiar manualmente la URL de descarga, de cambiar dicha URL en el servidor.

if($version != '1.5.1'){
	echo json_encode("Hay una nueva versión de Lealtag. ¿Deseas descargarla ahora?|0|http://www.lealtag.com/descarga");
}

?>
