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
// No tocar esta funcion consultarlo con Ismael antes :)
function validar_usuario($con, $usuario, $pass){
    // Consulta para verificar si el nombre de usuario y la contraseña son correctos
    //$resultado = mysqli_query($con, "SELECT pass, 'nutricionista' AS tipo FROM nutricionista WHERE usuario = '$usuario' UNION SELECT pass, 'paciente' AS tipo FROM paciente WHERE usuario ='$usuario';");
    $resultado = mysqli_query($con, "SELECT usuario, pass, tipo FROM nutricionista WHERE usuario = '$usuario' UNION ALL SELECT usuario,pass, tipo FROM paciente WHERE usuario = '$usuario';");
    if (mysqli_num_rows($resultado) > 0) { 
        $row = mysqli_fetch_assoc($resultado);
        // Se extrae el tipo del usuario
        $tipo = $row['tipo'];
        $pass_hash = $row['pass'];
        $usuario = $row['usuario']; 
        

        if (password_verify($pass, $pass_hash)) {
            $_SESSION['tipo'] = $tipo;
            $_SESSION['usuario'] = $usuario;
            $redirect = ($tipo == 3) ? "admin.php" :
                        (($tipo == 1) ? "nutricionista.php" :
                        (($tipo == 2) ? "paciente.php" : "index.php"));

            echo json_encode(["success" => true, "redirect" => $redirect]);
        } else {
            echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrecto"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
    }
}

function validar_token($token, $usuario, $tipo){
    $con = conexion();
    mysqli_query($con, "UPDATE nutricionista SET sesion = '$token' WHERE usuario = '$usuario';");
    $resultado = mysqli_query($con, "SELECT usuario, tipo FROM nutricionista WHERE sesion = '$token';");
    if (mysqli_num_rows($resultado) > 0) {         
            if ($tipo == 3) {
                header("Location: ../php/admin.php");
            }

            if ($tipo == 1) {
                header("Location: ../php/nutricionista.php");
            }

            if ($tipo == 2) {
                header("Location: ../php/paciente.php");
            }            
    } 
}

function borrar_sesion(){
    setcookie("token", "", time() - 3600, "/");
    session_destroy();
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
    
    if (isset($data['action']) && $data['action'] === "cerrar_sesion") {
        borrar_sesion();
        echo json_encode(["success" => true]);
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



/*************************FUNCIONES DE ADMIN.PHP********************************************** */

//función que obtiene lista de nutricionistas
function obtener_nutricionistas($con){
    $resultado = mysqli_query($con,"select usuario , nombre , apellido , email from nutricionista where tipo = 1");
    return $resultado;
}

//funcion que busca nutricionista por apellido
function buscar_nutricionista($con, $apellido) {
    unset($_SESSION['mensaje_nutricionista']);
    $apellido = mysqli_real_escape_string($con, $apellido);
    $resultado = mysqli_query($con, "select distinct usuario, nombre, apellido, email from nutricionista where tipo = 1 AND apellido LIKE '$apellido%'");
    return $resultado;
}

//función que obtiene lista de pacientes
function obtener_pacientes($con){
    $resultado = mysqli_query($con,"select usuario , nombre , apellido , email from paciente");
    return $resultado;
}

//función que obtiene lista de recetas
function listar_recetas($con){
    $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta");
    return $resultado;
}

//función que obtiene recetas por calorias
function buscar_calorias($con, $opcion){
    if($opcion == 1){
        $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta where calorias < 200;");
        return $resultado;
    }elseif($opcion == 2){
        $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta where calorias < 350;");
        return $resultado;
    }elseif($opcion == 3){
        $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta where calorias < 450;");
        return $resultado;
    }elseif($opcion == 4){
        $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta where calorias > 500;");
    return $resultado;
    }
}

//función para buscar pacientes
function listar_pacientes($con){
  $resultado = mysqli_query($con,"select usuario from paciente");
  return $resultado;
}

//función para buscar nutricionista
function listar_nutricionista($con){
    $resultado = mysqli_query($con,"select usuario from nutricionista where tipo = 1");
    return $resultado;
  }

//funcion para modificar el paciente y asignarlo al nutricionista
function asociar_paciente($con,$paciente,$nutricionista){
    $fila = mysqli_query($con, "select id_nutricionista from nutricionista where usuario = '$nutricionista'");
        if ($fila && mysqli_num_rows($fila) > 0) {
            $resultado = mysqli_fetch_assoc($fila);
            $id =(int) $resultado['id_nutricionista'];
            $busqueda = "update paciente set id_nutricionista = '$id' where usuario = '$paciente'";
        if (mysqli_query($con, $busqueda)) {
            $_SESSION['mensaje_asociar'] ="<h5 class='mensaje'>Paciente $paciente asociado correctamente</h5><h5> a nutricionista $nutricionista .</h5>";
        } else {
            $_SESSION['mensaje_asociar'] = "Error al asociar paciente: " . mysqli_error($con);
        }
    }
   }

 //función que busca receta por nombre
   function buscar_nombre_receta($con, $nombre) {
    if (empty($nombre)) {
        return false; 
    }
    $nombre = mysqli_real_escape_string($con, $nombre);
    $resultado = mysqli_query($con, "select nombre, ingredientes, calorias from receta where nombre LIKE '$nombre%'");
    return $resultado;
}   

//funcion que asocia una receta a un paciente
function asociar_receta($con,$receta,$paciente){
    $fila_paciente = mysqli_query($con, "select id_paciente from paciente where usuario = '$paciente'");
        if ($fila_paciente && mysqli_num_rows($fila_paciente) > 0) {
            $resultado_paciente = mysqli_fetch_assoc($fila_paciente);
            $id_paciente =(int) $resultado_paciente['id_paciente'];
        }
    $fila_receta = mysqli_query($con, "select id_receta from receta where nombre = '$receta'");
    if ($fila_receta && mysqli_num_rows($fila_receta) > 0) {
        $resultado_receta = mysqli_fetch_assoc($fila_receta);
        $id_receta =(int) $resultado_receta['id_receta'];
    }
    $query = "insert into lista (paciente,plato) values ('$id_paciente','$id_receta')";
    if (mysqli_query($con, $query)) {
        echo "<h2>Receta $receta asociada al paciente $paciente.";
    } else {
        echo "<h5 class='mensaje'>Error al asociar la receta: " . mysqli_error($con)."</h5>";
    }
   }

//funcion para crear nutricionista
   function crear_nutricionista_admin($con, $nombre, $apellido, $usuario, $pass, $email){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $resultado = mysqli_query($con, "insert into nutricionista (usuario, pass, nombre, apellido, email, tipo) values ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email', 1)");
    if (!$resultado) {
        unset($_SESSION['mensaje_nutricionista']);
        $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Error al crear usuario: " . mysqli_error($con)."</h5>";
       }
       $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Se ha creado el nutricionista $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";    
    }
   
//funcion para modificar nutricionista
   function modificar_nutricionista($con, $nombre, $apellido, $usuario, $pass, $email , $busqueda){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    unset($_SESSION['mensaje_nutricionista']);
    $resultado = mysqli_query($con, "update nutricionista set usuario = '$usuario' , pass = '$pass' , nombre = '$nombre', apellido = '$apellido' , email = '$email' where usuario = '$busqueda'");
    if (!$resultado) {
        $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Error al modificar usuario: " . mysqli_error($con)."</h5>";
      }
    $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Se ha modificado el nutricionista $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";    
    }

//funcion para eliminar nutricionista
    function eliminar_nutricionista($con, $usuario) {
        unset($_SESSION['mensaje_nutricionista']);
        $datos = mysqli_query($con, "select usuario, nombre, apellido, email from nutricionista where usuario = '$usuario'");
        $fila = mysqli_fetch_assoc($datos);
    
        if (!$fila) {
            echo "<h5 class='mensaje'>No se encontró ningún nutricionista con el usuario: $usuario.</h5>";
            return;
        }
        $usuario = $fila['usuario'];
        $nombre = $fila['nombre'];
        $apellido = $fila['apellido'];
        $email = $fila['email'];
        $resultado1 = mysqli_query($con,"delete from citas where nutricionista = (select id_nutricionista from nutricionista where usuario = '$usuario')");
        $resultado2 = mysqli_query($con, "delete from nutricionista where usuario = '$usuario'");
        
        if (!$resultado2) {
            $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Error al eliminar nutricionista: " . mysqli_error($con)."</h5>";
            return;
        }
        $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Se ha eliminado el nutricionista $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    }

//funcion que busca paciente por apellido
function buscar_paciente($con, $apellido) {
    unset($_SESSION['mensaje_paciente']);
    $apellido = mysqli_real_escape_string($con, $apellido);
    $resultado = mysqli_query($con, "select distinct usuario, nombre, apellido, email from paciente where apellido LIKE '$apellido%'");
    return $resultado;
}

//funcion para crear paciente
   function crear_paciente($con, $nombre, $apellido, $usuario, $pass, $email){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $resultado = mysqli_query($con, "insert into paciente (usuario, pass, nombre, apellido, email,id_nutricionista) values ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email',0)");
    if (!$resultado) {
        unset($_SESSION['mensaje_pacientes']);
        echo "Error al crear paciente: " . mysqli_error($con);
    }
    
    $_SESSION['mensaje_pacientes'] = "<h5 class='mensaje'>Se ha creado el paciente $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    }
 
    //funcion para modificar paciente
   function modificar_paciente($con, $nombre, $apellido, $usuario, $pass, $email , $busqueda){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    unset($_SESSION['mensaje_paciente']);
    $resultado = mysqli_query($con, "update paciente set usuario = '$usuario' , pass = '$pass' , nombre = '$nombre', apellido = '$apellido' , email = '$email' where usuario = '$busqueda'");
    if (!$resultado) {
        $_SESSION['mensaje_pacientes'] = "Error al modificar paciente: " . mysqli_error($con);
    }
    
    $_SESSION['mensaje_pacientes'] = "<h5 class='mensaje'>Se ha modificado el paciente $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    }

//funcion para eliminar paciente
    function eliminar_paciente($con, $usuario) {
        unset($_SESSION['mensaje_paciente']);
        $datos = mysqli_query($con, "select usuario, nombre, apellido, email from paciente where usuario = '$usuario'");
        $fila = mysqli_fetch_assoc($datos);
    
        if (!$fila) {
            echo "<h5 class='mensaje'>No se encontró ningún paciente con el usuario: $usuario.</h5>";
            return;
        }
        $usuario = $fila['usuario'];
        $nombre = $fila['nombre'];
        $apellido = $fila['apellido'];
        $email = $fila['email'];
    
        $resultado = mysqli_query($con, "delete from paciente where usuario = '$usuario'");
        
        if (!$resultado) {
            $_SESSION['mensaje_pacientes'] = "<h5 class='mensaje'>Error al eliminar paciente: " . mysqli_error($con)."</h5>";
            return;
        }
        $_SESSION['mensaje_pacientes'] = "<h5 class='mensaje'>Se ha eliminado el paciente $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    }

//funcion para crear cita
function crear_cita($con, $paciente, $nutricionista, $fecha, $hora) {
    $fecha = mysqli_real_escape_string($con, $fecha);
    $hora = mysqli_real_escape_string($con, $hora);
    $paciente = mysqli_real_escape_string($con, $paciente);
    $nutricionista = mysqli_real_escape_string($con, $nutricionista);
    
    $consulta_nutricionista = mysqli_query($con , "select id_nutricionista from nutricionista where usuario = '$nutricionista'");
    $fila = mysqli_fetch_assoc($consulta_nutricionista);
        if (!$fila) {
            $_SESSION['mensaje_asociar'] = "<h5 class='mensaje'>No se encontró ningún nutricionista con el usuario: $nutricionista.</h5>";
            return;
        }
        $id_nutricionista = $fila['id_nutricionista'];

    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
        if (!$fila) {
            $_SESSION['mensaje_asociar'] = "<h5 class='mensaje'>No se encontró ningún paciente con el usuario: $paciente.</h5>";
            return;
        }
        $id_paciente = $fila['id_paciente'];

    $resultado = mysqli_query($con,  "insert into citas (fecha, hora, paciente, nutricionista) 
            values ('$fecha', '$hora', '$id_paciente', '$id_nutricionista')");

     if (!$resultado) {
        $_SESSION['mensaje_asociar'] = "<h5 class='mensaje'>Error al crear la cita: " . mysqli_error($con)."</h5>";
        return;
    }
    $_SESSION['mensaje_asociar'] = "<h5 class='mensaje'>Se ha creado una cita el $fecha a la $hora,</h5><h5> para el nutricionista $nutricionista y el paciente $paciente.</h5>";
}

//funcion para buscar un nutricionista en la tabla
function obtener_tabla_citas($con ){
    $resultado = mysqli_query($con , "select distinct n.usuario from nutricionista n join citas c on n.id_nutricionista = c.nutricionista;");
       return $resultado;
}

// Función para obtener las citas por nutricionista
function obtener_citas_por_nutricionista($con, $nutricionista) {
    $query = "select distinct p.usuario , c.fecha ,c.hora FROM citas c join nutricionista n on n.id_nutricionista = c.nutricionista join paciente p on p.id_paciente = c.paciente where n.usuario = '$nutricionista'";
    return mysqli_query($con, $query);
}

//funcion para borrar una cita
function borrar_cita($con, $paciente, $nutricionista, $fecha, $hora) {
    $fecha = mysqli_real_escape_string($con, $fecha);
    $hora = mysqli_real_escape_string($con, $hora);
    $paciente = mysqli_real_escape_string($con, $paciente);
    $nutricionista = mysqli_real_escape_string($con, $nutricionista);
    
    $consulta_nutricionista = mysqli_query($con , "select id_nutricionista from nutricionista where usuario = '$nutricionista'");
    $fila = mysqli_fetch_assoc($consulta_nutricionista);
        if (!$fila) {
            echo "<h5 class='mensaje'>No se encontró ningún nutricionista con el usuario: $nutricionista.</h5>";
            return;
        }
        $id_nutricionista = $fila['id_nutricionista'];

    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
        if (!$fila) {
            echo "<h5 class='mensaje'>No se encontró ningún paciente con el usuario: $paciente.</h5>";
            return;
        }
        $id_paciente = $fila['id_paciente'];
    var_dump($fecha , $hora , $nutricionista, $paciente);
    $resultado = mysqli_query($con, "DELETE FROM citas WHERE fecha = '$fecha' AND hora = '$hora' AND paciente = '$id_paciente' AND nutricionista = '$id_nutricionista'");
if (!$resultado) {
    echo "<h5 class='mensaje'>Error al borrar la cita: " . mysqli_error($con)."</h5>";
} elseif (mysqli_affected_rows($con) === 0) {
    echo "<h5 class='mensaje'>No se encontró ninguna cita con esos datos.</h5>";
} else {
    echo "<h5 class='mensaje'>Se ha eliminado la cita del $fecha a las $hora, con el nutricionista $nutricionista y el paciente $paciente.</h5>";
    }
}

/*************************FUNCIONES DE PACIENTE.PHP********************************************** */
//función para ver ficha de paciente
function obtener_datos_paciente ($con){
    $usuario = $_SESSION['usuario'];
    $resultado = mysqli_query ($con, "select id_paciente, usuario, nombre, apellido, email, id_nutricionista FROM paciente WHERE usuario = '$usuario'");
    if ($row = mysqli_fetch_assoc($resultado)) {
        return $row;
    } else {
        return null;
    }


}


//función para modificar datos personales



//función para introducir medidas corporales



//función para modificar medidas corporales (sin modificar la fecha de introducción de las mismas)



//función para comparar medidas con objetivos



//función para mostrar menú semanal



//función para poner, modificar y cancelar citas



// Función para 










/****************************************************************************************************/
?>
