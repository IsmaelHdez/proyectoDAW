<?php
require_once("conexion.php");

$con = conexion();

$perfil = usuario_perfil($con);
$nombre = $perfil['nombre'];
$apellido = $perfil['apellido'];
$foto = $perfil['foto'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/header_alta.css">
    
    <title>NUTRIGO</title>
</head>
<body>
    <div id="encabezado">
        <img id="logo" src="https://res.cloudinary.com/dup8qzlzv/image/upload/v1742377568/logo_csilnx.png" alt="logo">
        
        <div class="enlaces">
            <a href="#que_comer">Que comer</a>
            <a href="cabecera.php?id=1">Nuestro objetivo</a>
            <a href="cabecera.php?id=2">Quienes somos</a>
            <a href="cabecera.php?id=3">Servicios</a>
            <a href="cabecera.php?id=4">Nuestro equipo</a>
            <a href="cabecera.php?id=5">Contacto</a>
        </div>
        
        <div id="perfil">
            <img src="<?php echo $foto; ?>" alt="Foto de perfil">
            <a href="nutricionista.php"><?php echo $nombre . " " . $apellido; ?></a>
        </div>

    </div>
</body>
</html>
