<?php
require("../html/header.html");
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($id) {
    case '1':
        require("../html/objetivo.html");
        break;
    case '2':
        echo "Página para el Enlace 2";
        break;
    case '3':
        echo "Página para el Enlace 3";
        break;
    case '4':
        echo "Página para el Enlace 4";
        break;
    default:
        echo "Página por defecto o error: No se ha seleccionado un enlace válido.";
}
require("../html/footer.html");
?>