<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


require("conexion.php");
require("../html/header.html");
require("../html/crear_nutricionista.html");
require("../html/footer.html");

?>