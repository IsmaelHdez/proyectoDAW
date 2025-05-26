<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluye archivos necesarios
require("conexion.php");
require("../html/header.html");
require("../html/login.html"); 
require("../html/footer.html");


?>