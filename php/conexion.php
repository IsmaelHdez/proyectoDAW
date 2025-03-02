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
    $resultado = mysqli_query($con, "SELECT pass, tipo FROM nutricionista WHERE usuario = '$usuario';");
    
    if (mysqli_num_rows($resultado) > 0) { 
        $row = mysqli_fetch_assoc($resultado);
        // Se extrae el tipo del usuario
        $tipo = $row['tipo'];
        $pass_hash = $row['pass']; 
        
        if (password_verify($pass, $pass_hash)) {
            $_SESSION['tipo'] = $tipo;
            $redirect = ($tipo == 3) ? "admin.php" :
                        (($tipo == 1) ? "nutricionista.php" :
                        (($tipo == 2) ? "paciente.php" : "index.php"));

            echo json_encode(["success" => true, "redirect" => $redirect]);
        } else {
            echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrecto"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrecto"]);
    }
}

// Procesa la solicitud JSON desde JavaScript
$data = json_decode(file_get_contents("php://input"), true);
if ($data) {
    $con = conexion();
    if (isset($data['nombre_crear']) && isset($data['apellido_crear']) && isset($data['usuario_crear']) && isset($data['pass_crear']) && isset($data['email_crear'])) {
        crear_usuario($con, $data['nombre_crear'], $data['apellido_crear'], $data['usuario_crear'], $data['pass_crear'], $data['email_crear'], 1);
    } 

    if (isset($data['usuario']) && isset($data['pass'])) {
        validar_usuario($con, $data['usuario'], $data['pass']);
    }
    
    
}

function crear_usuario($con, $nombre, $apellido, $usuario, $pass, $email, $tipo){
    $resultado = mysqli_query($con, "SELECT * FROM nutricionista WHERE email = '$email' and usuario = '$usuario';");

    if (mysqli_num_rows($resultado) == 0){
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
        mysqli_query($con, "INSERT INTO nutricionista (usuario, pass, nombre, apellido, email, tipo) VALUES ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email', '$tipo');");
        $_SESSION["tipo"] = $tipo;
        $redirect1 = "nutricionista.php";
        echo json_encode(["success" => true, "redirect" => $redirect1]);
    } else{
        echo json_encode(["success" => false, "message" => "Usuario o correo ya registrado"]);
    }
}

?>
