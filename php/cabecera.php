<?php
require("../html/header.html");
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($id) {
    case '1':
        require("../html/objetivo.html");
        break;
    case '2':
        require("../html/quienes_somos.html");
        break;
    case '3':
        require("../html/secciones.html");
        break;
    case '4':
        require("../html/equipo.html");
        break;
    case '5':
        require("contacto.php");
        break;
    default:
        echo "Página por defecto o error: No se ha seleccionado un enlace válido.";
}
require("../html/footer.html");
?>