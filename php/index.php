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

// Conecta a la base de datos
$con = conexion();
if (!$con) {
    die("Error al conectar con la base de datos");
}

// Verifica si se enviaron los datos del formulario
if (isset($_POST["usuario"]) && isset($_POST["pass"])) {
    // Almacena el usuario en la sesión
    $_SESSION['usuario'] = $_POST["usuario"];
    validar_usuario($con, $_POST["usuario"], $_POST["pass"]);
}
?>