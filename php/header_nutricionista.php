<?php
require_once("conexion.php");

$con = conexion();

$perfil = usuario_perfil($con);
$nombre = $perfil['nombre'];
$apellido = $perfil['apellido'];
$tipo = $perfil['tipo'];
$foto = $perfil['foto'];

$direccion = "";

if ($tipo == 1) {
    $direccion = "nutricionista.php";
}

if ($tipo == 2) {
    $direccion = "paciente.php";
}

if ($tipo == 3) {
    $direccion = "admin.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/header_alta_nutricionista.css">
    <link rel="stylesheet" href="../CSS/global.css">    
    <title>Nutrigo</title>
</head>
<body>
    <div id="encabezado">
        <img id="logo" src="https://res.cloudinary.com/dup8qzlzv/image/upload/v1742377568/logo_csilnx.png" alt="logo">
        
        <div class="enlaces">
            <div class="dropdown">
                <a class="dropdown-toggle">Pacientes</a>
                <div class="dropdown-menu">
                    <a onclick="mostrarSeccion('buscador_paciente','contenedor_tabla_paciente')">Buscar por apellido</a>
                    <a onclick="mostrarSeccion('crear_paciente','contenedor_tabla_paciente')">Crear</a>
                    <a onclick="mostrarSeccion('modificar_paciente','contenedor_tabla_paciente')">Modificar</a>
                    <a onclick="mostrarSeccion('borrar_paciente','contenedor_tabla_paciente')">Eliminar</a>
                </div>
            </div>
                        <div class="dropdown">
                <a class="dropdown-toggle">Recetas</a>
                <div class="dropdown-menu">
                    <a onclick="mostrarSeccion('crear_receta','tabla_contenedor_recetas')">Crear</a>
                    <a onclick="mostrarSeccion('modificar_receta','tabla_contenedor_recetas')">Modificar</a>
                    <a onclick="mostrarSeccion('borrar_receta','tabla_contenedor_recetas')">Eliminar</a>
                </div>
            </div>
                        <div class="dropdown">
                <a class="dropdown-toggle">Calendario</a>
                <div class="dropdown-menu">
                    <a onclick="mostrarSeccion('asignar_calendario','tabla_calendario')">Asignar receta</a>
                </div>
            </div>
                        <div class="dropdown">
                <a class="dropdown-toggle">Citas</a>
                <div class="dropdown-menu">
                    <a onclick="mostrarSeccion('crear_cita','tabla_citas')">Crear</a>
                    <a onclick="mostrarSeccion('borrar_cita','tabla_citas')">Eliminar</a>
                </div>
            </div>
        </div>

        <div id="perfil">
            <img src="<?php echo $foto; ?>" alt="Foto de perfil">
            <div class="perfil-opciones">
                <a href="<?php echo $direccion; ?>"><?php echo $nombre . " " . $apellido; ?></a>
                <a id="cerrarSesion">Cerrar sesión</a>
            </div>
        </div>

    </div>
</body>
</html>
