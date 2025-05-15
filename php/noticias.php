<?php
require_once("conexion.php");

// Incluye el header adecuado según si la variable $_SESSION['usuario'] tiene contenido
if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    require("header_alta.php");
} else {
    require("../html/header.html");
}

function obtener_noticia_pagina($url){
    // Cambiar dominio de dropbox para obtener contenido raw directamente
    if (strpos($url, "dropbox.com") !== false) {
        $url = preg_replace('/^https:\/\/www\.dropbox\.com/', 'https://dl.dropboxusercontent.com', $url);
        $url = preg_replace('/\?dl=0$/', '', $url); // Elimina ?dl=0 si está al final
    }

    $contenido = file_get_contents($url);
    echo $contenido !== false ? $contenido : "No se pudo cargar la noticia.";
}


if (isset($_GET['url'])) {
    $contenido = obtener_noticia_pagina($_GET['url']);
} else {
    $contenido = "URL no especificada.";
}

require("../html/footer.html");
?>

