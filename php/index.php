<?php
// Inicia la sesión si no se ha iniciado ya
session_start();

require("conexion.php");

// Incluye el header adecuado según si la variable $_SESSION['usuario'] tiene contenido
if (isset($_COOKIE['token']) && isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    $token = $_COOKIE['token'];
    $usuario = $_SESSION['usuario'];
    $tipo = $_SESSION['tipo'];
    validar_token($token, $usuario, $tipo);
    require("header_alta.php");
} else {
    require("../html/header.html");
}

// Incluye los otros archivos
require("principal.php");
require("targetas_nutricionistas.php");
require("../html/footer.html");
?>