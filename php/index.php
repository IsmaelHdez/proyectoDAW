<?php
// Inicia la sesión al principio del script
session_start();

// Incluye archivos necesarios
require("conexion.php");
require("../html/index.html"); // Verifica que la ruta sea correcta

if (isset($_COOKIE['token']) && isset($_SESSION['usuario'])) {
    $token = $_COOKIE['token'];
    $usuario = $_SESSION['usuario'];
    $tipo = $_SESSION['tipo'];
    validar_token($token, $usuario, $tipo);
}
?>