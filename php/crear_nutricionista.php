<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



require("../html/header.html");
require("crear_nutricionista_formulario.php");
require("../html/footer.html");

?>