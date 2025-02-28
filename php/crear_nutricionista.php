<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require("../html/crear_nutricionista.html");
require("conexion.php");

if (isset($_GET['error'])) {
    echo "<p style='color: red; font-weight: bold;'>" . htmlspecialchars($_GET['error']) . "</p>";
}

if(isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["user"]) && isset($_POST["pass"]) && isset($_POST["email"])){

    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $usuario = $_POST["user"];
    $pass = $_POST["pass"];
    $email = $_POST["email"];
    $_SESSION["tipo"] = 1;
    $tipo = $_SESSION["tipo"];

    $con = conexion();
    echo crear_usuario($con, $nombre, $apellido, $usuario, $pass, $email, $tipo);

}

?>