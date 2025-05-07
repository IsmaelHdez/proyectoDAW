<?php
require("conexion.php");

$con = conexion();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.css">
    <script src="../js/validacion_crear_usuario.js" defer></script>
    <title>Login</title>
</head>
<body>
    <div id="elementos">
        <div id="login">
            <div id="formulario_login">
                <h1>Registrar Nutricionista</h1>
                <div id="formulario">
                    <form id="formulario_nutricionista" method="POST">
                        <label for="nombre">Nombre: </label>
                        <input type="text" name="nombre" id="nombre" required><br/>
                        <label for="apellido">Apellido: </label>
                        <input type="text" name="apellido" id="apellido" required><br/>
                        <label for="usuario">Usuario: </label>
                        <input type="text" name="user" id="usuario" required><br/>
                        <label for="pass">Contraseña: </label>
                        <input type="password" name="pass" id="pass" required><br/>
                        <label for="pass2">Repite la contraseña: </label>
                        <input type="password" name="pass2" id="pass2" required><br/>
                        <label for="email">Email: </label>
                        <input type="text" name="email" id="email" required><br/>
                        <select id="opciones" name="opciones" required>
                            <option value="">Seleccione una opción</option>
                                <?php
                                    $especialidades = tipo_nutricionista($con);
                                    foreach ($especialidades as $especialidad) {
                                        echo $especialidad;
                                    }                    
                                ?>
                            </select>
                        <input type="file" name="subir_foto" id="subir_foto" required>
                        <input type="submit" name="registrar" id="registrar" value="Registrar">
                    </form>
                    <!-- Mensaje de error debajo del formulario -->
                    <div id="mensaje_error" style="color: red; display: none;"></div>
                </div>
            </div>
        </div>    
    </div>
</body>
</html>