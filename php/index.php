<?php
// Inicia la sesión al principio del script
session_start();

// Si ya hay una sesión activa, la destruye y crea una nueva
if (isset($_SESSION['usuario'])) {
    session_destroy();
    session_start();
}

// Incluye archivos necesarios
require("conexion.php");
require("../html/index.html"); // Verifica que la ruta sea correcta


?>