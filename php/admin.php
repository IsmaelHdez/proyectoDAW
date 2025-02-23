<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SESSION["tipo"] != 3){
    header("Location: index.php");
}

echo $_SESSION["tipo"];
?>