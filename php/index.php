<?php
// Inicia la sesión al principio del script
session_start();

// Si ya hay una sesión activa, la destruye y crea una nueva
if (isset($_SESSION['usuario'])) {
    session_destroy();
    session_start();
}
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.css">
    <title>Login</title>
</head>
<body>
    <header>
        <h1>Cocinator</h1>
    </header>
    <main>
        <div id="formulario_login">
            <h1>Acceder a cuenta usuario</h1>
            <form action="" method="POST">
                <label for="">Usuario: </label>
                <input type="text" name="usuario" id="text_usuario" required><br/>
                <label for="">Contraseña: </label>
                <input type="password" name="pass" id="text_pass" required><br/>
                <input type="submit" name="ingresar" id="ingresar" value="Ingresar">
            </form>
            <a href="../php/crear_nutricionista.php">Crear nuevo usuario...</a>
        </div>
    </main>
    <footer>
        <h5>Creado por grupo 8 Linkia</h5>
    </footer>
</body>
</html>';

// Incluye archivos necesarios
require("conexion.php");
//require("../html/index.html"); // Verifica que la ruta sea correcta

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