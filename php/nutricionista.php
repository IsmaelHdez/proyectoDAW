<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SESSION["tipo"] != 1){
    header("Location: index.php");
}

$_SESSION["tipo"]
?>