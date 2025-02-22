<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION["tipo"]) != 0){
    header("Location: index.php");
}
?>