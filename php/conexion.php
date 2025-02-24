<?php
// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require("datos.php");

function conexion(){
    // Conexión al servidor de base de datos
    $con = mysqli_connect($GLOBALS["host"], $GLOBALS["user"], $GLOBALS["pass"]) or die("Error al conectar la base de datos");

    // Seleccionar la base de datos a usar
    mysqli_select_db($con, $GLOBALS["db_name"]);

    return $con;
}

function validar_usuario($con, $usuario, $pass){
    // Consulta para verificar si el nombre de usuario y la contraseña son correctos
    $resultado = mysqli_query($con, "SELECT pass, tipo FROM Usuario WHERE nombre = '$usuario';");
    
    if (mysqli_num_rows($resultado) > 0) { 
        $row = mysqli_fetch_assoc($resultado);
        // Se extrae el tipo del usuario
        $tipo = $row['tipo'];
        $pass_hash = $row['pass']; 
        
        // Se guarda el tipo de usuario en la sesión
        $_SESSION['tipo'] = $tipo; 
        
        // Verificar la contraseña usando password_verify()
        if (password_verify($pass, $pass_hash)) {
            // Redirige al usuario según su tipo
            if ($tipo == 3) {
                header("Location: admin.php");
            } elseif ($tipo == 1) {
                header("Location: nutricionista.php");
            } elseif ($tipo == 2) {
                header("Location: paciente.php");
            } else {
                header("Location: index.php");
            }
        } else {
            // Si la contraseña es incorrecta
            header("Location: index.php");
        }
    } else {
        // Si no se encuentra el usuario
        header("Location: index.php");
    }
}


// Función para obtener el número de filas
function obtener_num_filas($resultado){
    // Devuelve el número de filas
    return mysqli_num_rows($resultado);
}

function crear_usuario($con, $nombre, $apellido, $usuario, $pass, $email, $tipo){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    mysqli_query($con, "INSERT INTO usuario (usuario, pass, nombre, apellido, email, tipo) VALUES ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email', '$tipo');");
    header("Location: nutricionista.php");
}

?>