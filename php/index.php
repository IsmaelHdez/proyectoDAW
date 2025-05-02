<?php
// Inicia la sesión si no se ha iniciado ya
session_start();

// Incluye el header adecuado según si la variable $_SESSION['usuario'] tiene contenido
if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    require("header_alta.php");
} else {
    require("../html/header.html");
}

// Incluye los otros archivos
require("../html/principal.html");
require("targetas_nutricionistas.php");
require("../html/footer.html");
?>