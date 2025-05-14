<?php
require_once("conexion.php");

$con = conexion();
$_SESSION['id_nutricionista'] =(int) obtener_datos_nutricionista($con);

$resultado = mostrar_panel_nutricionista($con , $_SESSION['id_nutricionista']);
$datos = mysqli_fetch_assoc($resultado);
$foto_url = (!empty($foto)) ? $foto : "https://res.cloudinary.com/dup8qzlzv/image/upload/v1744743726/sin_foto_hjvtev.jpg";

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
                <a class="dropdown-toggle">Nutricionistas</a>
                <div class="dropdown-menu">
                    <a onclick="mostrarSeccion('buscador_nutricionista','contenedor_tabla_nutricionista')">Buscar por apellido</a>
                    <a onclick="mostrarSeccion('crear_nutricionista','contenedor_tabla_nutricionista')">Crear</a>
                    <a onclick="mostrarSeccion('modificar_nutricionista','contenedor_tabla_nutricionista')">Modificar</a>
                    <a onclick="mostrarSeccion('borrar_nutri','contenedor_tabla_nutricionista')">Eliminar</a>
                </div>
            </div>
            <div class="dropdown">
                <a class="dropdown-toggle">Pacientes</a>
                <div class="dropdown-menu">
                    <a onclick="mostrarSeccion('buscador_paciente','contenedor_tabla_paciente')">Buscar por apellido</a>
                    <a onclick="mostrarSeccion('crear_paciente','contenedor_tabla_paciente')">Creación</a>
                    <a onclick="mostrarSeccion('modificar_paciente','contenedor_tabla_paciente')">Modificación</a>
                    <a onclick="mostrarSeccion('borrar_paci','contenedor_tabla_paciente')">Eliminación</a>
                </div>
            </div>          
        </div>

        <div id="perfil">
            <img src="<?php echo $foto_url; ?>" alt="Foto de perfil">
            <div class="perfil-opciones">
                <a href="<?php echo $direccion; ?>"><?php echo $nombre . " " . $apellido; ?></a>
                <a id="cerrarSesion">Cerrar sesión</a>
            </div>
        </div>

    </div>
</body>
</html>
