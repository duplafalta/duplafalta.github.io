<?php
/* -----------------------------------------------------------------*/
/*                      Inter4u - Eldon Pinheiro                    */
/* -----------------------------------------------------------------*/
require('config.php');
if ($instalado == "n") {
    header("Location: instalar.php");
} elseif (file_exists('instalar.php')) {
    echo "<b>Apague o arquivo instalar.php para sua segurança!</b>";
} else {
    header("Location: mural.php");
} 
?>