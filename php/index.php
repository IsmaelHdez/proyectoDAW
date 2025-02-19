<?php
// Verifica si ya hay una sesión activa, si existe una sesion la destrulle y luego crea una nueva
if (isset($_SESSION['usuario'])) {
    session_destroy();
    session_start(); 
}
// Incluye el archivo HTML para la interfaz de usuario, y el archivo de la conexion de base de dato
require("index.html");
require("conexion.php");

// Llama a la función conectar() para establecer la conexión con la base de datos
$con = conectar();

// Verifica si se han enviado los datos 'usuario' y 'pass' a través del formulario
if (isset($_POST["usuario"]) && isset($_POST["pass"])) {
    // Almacena el usuario en la sesión
    $_SESSION['usuario'] = $_POST["usuario"];
    echo "Conectado a la base de datos"
}
?>