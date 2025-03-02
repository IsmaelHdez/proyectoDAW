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
            header("Location: index.php?error=Usuario y contraseña incorrecto");
        }
    } else {
        // Si no se encuentra el usuario
        header("Location: index.php?error=Usuario y contraseña incorrecto");
    }
}


// Función para obtener el número de filas
function obtener_num_filas($resultado){
    // Devuelve el número de filas
    return mysqli_num_rows($resultado);
}

function crear_usuario($con, $nombre, $apellido, $usuario, $pass, $email, $tipo){
    $resultado = mysqli_query($con, "SELECT * FROM nutricionista WHERE email = '$email';");
    if (mysqli_num_rows($resultado) > 0){
        header("Location: crear_nutricionista.php?error=Ya existe un usuario con este email");
    } else{
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
        mysqli_query($con, "INSERT INTO nutricionista (usuario, pass, nombre, apellido, email, tipo) VALUES ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email', '$tipo');");
        header("Location: nutricionista.php");
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
    if (empty($apellido)) {
        return false; 
    }
    $apellido = mysqli_real_escape_string($con, $apellido);
    $resultado = mysqli_query($con, "select usuario, nombre, apellido, email from nutricionista where tipo = 1 AND apellido LIKE '$apellido%'");
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
            echo "Paciente asociado correctamente.";
        } else {
            echo "Error al asociar paciente: " . mysqli_error($con);
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
        echo "Receta asociada correctamente.";
    } else {
        echo "Error al asociar la receta: " . mysqli_error($con);
    }
   }

//funcion para crear nutricionista
   function crear_nutricionista($con, $nombre, $apellido, $usuario, $pass, $email){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $resultado = mysqli_query($con, "insert into nutricionista (usuario, pass, nombre, apellido, email, tipo) values ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email', 1)");
    if (!$resultado) {
        echo "Error al crear usuario: " . mysqli_error($con);
    }
    
    echo "<p>Se ha creado el usuario $usuario con nombre completo : $nombre $apellido y email : $email .</p>";
    }
   
//funcion para modificar nutricionista
   function modificar_nutricionista($con, $nombre, $apellido, $usuario, $pass, $email , $busqueda){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $resultado = mysqli_query($con, "update nutricionista set usuario = '$usuario' , pass = '$pass' , nombre = '$nombre', apellido = '$apellido' , email = '$email' where usuario = '$busqueda'");
    if (!$resultado) {
        echo "Error al modificar usuario: " . mysqli_error($con);
    }
    
    echo "<p>Se ha modificado el usuario $usuario con nombre completo : $nombre $apellido y email : $email.</p>";
    }
//funcion para crear paciente
   function crear_paciente($con, $nombre, $apellido, $usuario, $pass, $email){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $resultado = mysqli_query($con, "insert into paciente (usuario, pass, nombre, apellido, email,id_nutricionista) values ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email',1)");
    if (!$resultado) {
        echo "Error al crear paciente: " . mysqli_error($con);
    }
    
    echo "<p>Se ha creado el paciente $usuario con nombre completo : $nombre $apellido y email : $email.</p>";
    }
 
    //funcion para modificar paciente
   function modificar_paciente($con, $nombre, $apellido, $usuario, $pass, $email , $busqueda){
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $resultado = mysqli_query($con, "update paciente set usuario = '$usuario' , pass = '$pass' , nombre = '$nombre', apellido = '$apellido' , email = '$email' where usuario = '$busqueda'");
    if (!$resultado) {
        echo "Error al modificar paciente: " . mysqli_error($con);
    }
    
    echo "<p>Se ha modificado el paciente $usuario con nombre completo : $nombre $apellido y email : $email.</p>";
    }

    //funcion para eliminar nutricionista
    function eliminar_nutricionista($con, $usuario) {
        $datos = mysqli_query($con, "select usuario, nombre, apellido, email from nutricionista where usuario = '$usuario'");
        $fila = mysqli_fetch_assoc($datos);
    
        if (!$fila) {
            echo "<p>No se encontró ningún nutricionista con el usuario: $usuario.</p>";
            return;
        }
        $usuario = $fila['usuario'];
        $nombre = $fila['nombre'];
        $apellido = $fila['apellido'];
        $email = $fila['email'];
    
        $resultado = mysqli_query($con, "delete from nutricionista where usuario = '$usuario'");
        
        if (!$resultado) {
            echo "Error al eliminar nutricionista: " . mysqli_error($con);
            return;
        }
        echo "<p>Se ha eliminado el nutricionista $usuario con nombre completo: $nombre $apellido y email: $email.</p>";
    }
//funcion para eliminar paciente
    function eliminar_paciente($con, $usuario) {
        $datos = mysqli_query($con, "select usuario, nombre, apellido, email from paciente where usuario = '$usuario'");
        $fila = mysqli_fetch_assoc($datos);
    
        if (!$fila) {
            echo "<p>No se encontró ningún paciente con el usuario: $usuario.</p>";
            return;
        }
        $usuario = $fila['usuario'];
        $nombre = $fila['nombre'];
        $apellido = $fila['apellido'];
        $email = $fila['email'];
    
        $resultado = mysqli_query($con, "delete from paciente where usuario = '$usuario'");
        
        if (!$resultado) {
            echo "Error al eliminar paciente: " . mysqli_error($con);
            return;
        }
        echo "<p>Se ha eliminado el paciente $usuario con nombre completo: $nombre $apellido y email: $email.</p>";
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
            echo "<p>No se encontró ningún nutricionista con el usuario: $nutricionista.</p>";
            return;
        }
        $id_nutricionista = $fila['id_nutricionista'];

    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
        if (!$fila) {
            echo "<p>No se encontró ningún paciente con el usuario: $paciente.</p>";
            return;
        }
        $id_paciente = $fila['id_paciente'];

    $resultado = mysqli_query($con,  "insert into citas (fecha, hora, paciente, nutricionista) 
            values ('$fecha', '$hora', '$id_paciente', '$id_nutricionista')");

     if (!$resultado) {
        echo "Error al crear la cita: " . mysqli_error($con);
        return;
    }
    echo "<p>Se ha creado una cita el $fecha a la $hora, para el nutricionista $nutricionista y el paciente $paciente.</p>";
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

function borrar_cita($con, $paciente, $nutricionista, $fecha, $hora) {
    $fecha = mysqli_real_escape_string($con, $fecha);
    $hora = mysqli_real_escape_string($con, $hora);
    $paciente = mysqli_real_escape_string($con, $paciente);
    $nutricionista = mysqli_real_escape_string($con, $nutricionista);
    
    $consulta_nutricionista = mysqli_query($con , "select id_nutricionista from nutricionista where usuario = '$nutricionista'");
    $fila = mysqli_fetch_assoc($consulta_nutricionista);
        if (!$fila) {
            echo "<p>No se encontró ningún nutricionista con el usuario: $nutricionista.</p>";
            return;
        }
        $id_nutricionista = $fila['id_nutricionista'];

    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
        if (!$fila) {
            echo "<p>No se encontró ningún paciente con el usuario: $paciente.</p>";
            return;
        }
        $id_paciente = $fila['id_paciente'];
    var_dump($fecha , $hora , $nutricionista, $paciente);
    $resultado = mysqli_query($con, "DELETE FROM citas WHERE fecha = '$fecha' AND hora = '$hora' AND paciente = '$id_paciente' AND nutricionista = '$id_nutricionista'");
if (!$resultado) {
    echo "Error al borrar la cita: " . mysqli_error($con);
} elseif (mysqli_affected_rows($con) === 0) {
    echo "No se encontró ninguna cita con esos datos.";
} else {
    echo "<p>Se ha eliminado la cita del $fecha a las $hora, con el nutricionista $nutricionista y el paciente $paciente.</p>";
    }
}

/****************************************************************************************************/
?>