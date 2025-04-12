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
                        (($tipo == 2) ? "paciente.php" : "login.php"));

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
    mysqli_query($con, "UPDATE nutricionista SET sesion = '$token' WHERE usuario = '$usuario' AND tipo = 1 OR tipo = 3;");
    mysqli_query($con, "UPDATE paciente SET sesion = '$token' WHERE usuario = '$usuario' AND tipo = 2;");
    $resultado = mysqli_query($con, "SELECT usuario, tipo FROM nutricionista WHERE sesion = '$token' UNION ALL SELECT usuario, tipo FROM paciente WHERE sesion = '$token';");
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
    // Revisar si los datos de creación de usuario están presentes
    if (isset($data['nombre_crear']) && isset($data['apellido_crear']) && isset($data['usuario_crear']) && isset($data['pass_crear']) && isset($data['email_crear']) && isset($data['image'])) {
        
        // Subir la imagen si existe
        $image_url = null;
        if (isset($data['image']) && !empty($data['image'])) {
            // Convertir la imagen base64 a un archivo temporal
            $image_url = subir_imagen_cloudinary_registro($data['image']);
        }

        // Crear usuario en la base de datos con o sin imagen
        crear_usuario($con, $data['nombre_crear'], $data['apellido_crear'], $data['usuario_crear'], $data['pass_crear'], $data['email_crear'], 1, $image_url);
    } 

    // Validación de usuario existente
    if (isset($data['usuario']) && isset($data['pass'])) {
        validar_usuario($con, $data['usuario'], $data['pass']);
    }
    
    // Cerrar sesión
    if (isset($data['action']) && $data['action'] === "cerrar_sesion") {
        borrar_sesion();
        echo json_encode(["success" => true]);
    }
}

// Función para crear el usuario en la base de datos
function crear_usuario($con, $nombre, $apellido, $usuario, $pass, $email, $tipo, $imagen_url){
    $resultado = mysqli_query($con, "SELECT * FROM nutricionista WHERE email = '$email' and usuario = '$usuario';");

    if (mysqli_num_rows($resultado) == 0){
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);

        // Si hay una imagen, la insertamos también
        if ($imagen_url) {
            mysqli_query($con, "INSERT INTO nutricionista (usuario, pass, nombre, apellido, email, tipo, foto) VALUES ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email', '$tipo', '$imagen_url');");
        } else {
            mysqli_query($con, "INSERT INTO nutricionista (usuario, pass, nombre, apellido, email, tipo) VALUES ('$usuario', '$hash_pass', '$nombre', '$apellido', '$email', '$tipo');");
        }
        
        $_SESSION["tipo"] = $tipo;
        $redirect1 = "nutricionista.php";
        echo json_encode(["success" => true, "redirect" => $redirect1]);
    } else{
        echo json_encode(["success" => false, "message" => "Usuario o correo ya registrado"]);
    }
}

function obtener_nutricionista($con) {
    $resultado = mysqli_query($con, "SELECT * FROM nutricionista ORDER BY id_nutricionista DESC LIMIT 4;");
    if (mysqli_num_rows($resultado) > 0) {
        while ($row = $resultado->fetch_assoc()) {
            if ($row['tipo'] == 1) {
                $nutricionistas[] = $row;
            }
        }
    }
    return $nutricionistas;
}

// Función para subir la imagen a Cloudinary
function subir_imagen_cloudinary_registro($imagen_base64) {
    $cloud_name = "dup8qzlzv"; // Tu Cloud name de Cloudinary
    $api_key = "257596798154478"; // Tu API key de Cloudinary
    $upload_url = "https://api.cloudinary.com/v1_1/$cloud_name/image/upload";
    
    // Decodificar la imagen base64
    $image_data = base64_decode(str_replace('data:image/jpeg;base64,', '', $imagen_base64));

    // Crear un archivo temporal para la imagen
    $tmp_file = tempnam(sys_get_temp_dir(), 'cloudinary_');
    file_put_contents($tmp_file, $image_data);

    $data = [
        "file" => new CURLFile($tmp_file),
        "upload_preset" => "ml_default" // El nombre de tu upload preset
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $upload_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    
    // El URL seguro de la imagen subida
    return isset($result["secure_url"]) ? $result["secure_url"] : null;
}

/*************************GESTIÓN DE IMÁGENES********************************************** */

function subir_imagen_cloudinary($imagen_tmp) {
    $cloud_name = "dup8qzlzv";
    $api_key = "257596798154478";
    $upload_url = "https://api.cloudinary.com/v1_1/$cloud_name/image/upload";

    $data = [
        "file" => new CURLFile($imagen_tmp),
        "upload_preset" => "ml_default"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $upload_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    
    return isset($result["secure_url"]) ? $result["secure_url"] : null;
}


function eliminar_imagen_cloudinary($url_actual) {
    if (!$url_actual) {
        return false;
    }

    $cloud_name = "dup8qzlzv"; 
    $api_key = "257596798154478"; 
    $api_secret = "ejH0vRb5LxDDF8tck6F_toX0XLs";

   // Extraer el `public_id` de la URL de la imagen
   $parsed_url = parse_url($url_actual);
   $path_parts = explode("/", $parsed_url["path"]);
   $public_id_with_extension = end($path_parts);
   $public_id = pathinfo($public_id_with_extension, PATHINFO_FILENAME); // Quitar extensión

   $delete_url = "https://api.cloudinary.com/v1_1/$cloud_name/image/destroy";

   // Generar firma para autenticación
   $timestamp = time();
   $string_to_sign = "public_id=$public_id&timestamp=$timestamp$api_secret";
   $signature = sha1($string_to_sign);

   // Datos para la solicitud de eliminación
   $data = [
       "public_id" => $public_id,
       "api_key" => $api_key,
       "timestamp" => $timestamp,
       "signature" => $signature
   ];

   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $delete_url);
   curl_setopt($ch, CURLOPT_POST, true);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

   $response = curl_exec($ch);
   curl_close($ch);

   $result = json_decode($response, true);

   return isset($result["result"]) && $result["result"] === "ok";
}



/*************************FUNCIONES DE ADMIN.PHP********************************************** */

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


  // Función para crear nutricionista
function crear_nutricionista_cloudinary($con, $nombre, $apellido, $email, $usuario, $pass = null, $foto = null) {
    unset($_SESSION['mensaje_nutricionista']);
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);

    if ($foto != null) {
        $query = "insert into nutricionista (usuario, pass, nombre, apellido, email, foto, tipo)values 
        ('$usuario' , '$hash_pass' ,'$nombre', '$apellido', '$email', '$foto' , 1)";
    }else{
    $query = "insert into nutricionista (usuario, pass, nombre, apellido, email, tipo)values 
    ('$usuario' , '$hash_pass' ,'$nombre', '$apellido', '$email', 1)";
    }

    if (mysqli_query($con, $query)) {
        $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Se ha creado el nutricionista $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    } else {
        $_SESSION['mensaje_nutricionista'] = "Tus datos no se han podido modificar.";
    }
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

  // Función para modificar nutricionista
  function modificar_nutricionista_cloudinary($con, $nombre, $apellido, $email, $usuario, $pass = null, $foto = null, $busqueda) {
    unset($_SESSION['mensaje_nutricionista']);

    $query = "update nutricionista set  usuario = '$usuario' ,pass ='$pass' ,nombre = '$nombre', apellido = '$apellido', email = '$email'";

    if ($foto) {
        $query .= ", foto = '$foto'";
    }

    $query .= " where usuario = '$busqueda'";

    if (mysqli_query($con, $query)) {
        $_SESSION['mensaje_nutricionista'] = "<h5 class='mensaje'>Se ha modificado el nutricionista $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    } else {
        $_SESSION['mensaje_nutricionista'] = "<h5>Tus datos no se han podido modificar.</h5>";
    }
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
        $resultado1 = mysqli_query($con,"delete from citas where nutricionista in (select id_nutricionista from nutricionista where usuario = '$usuario');");
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

 // Función para crear paciente
function crear_paciente_cloudinary($con, $nombre, $apellido, $email, $usuario, $pass = null, $foto = null) {
    unset($_SESSION['mensaje_pacientes']);
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $query = "insert into paciente (usuario, pass, nombre, apellido, email,foto,id_nutricionista)values 
    ('$usuario' , '$hash_pass' ,'$nombre', '$apellido', '$email', '$foto' , null)";
    
    if ($foto != null) {
        $query = "insert into paciente (usuario, pass, nombre, apellido, email,foto,id_nutricionista)values 
        ('$usuario' , '$hash_pass' ,'$nombre', '$apellido', '$email', '$foto' , null)";
    }else{
        $query = "insert into paciente (usuario, pass, nombre, apellido, email,id_nutricionista)values 
        ('$usuario' , '$hash_pass' ,'$nombre', '$apellido', '$email', null)";
    }
    
    if (mysqli_query($con, $query)) {
        $_SESSION['mensaje_pacientes'] = "<h5 class='mensaje'>Se ha creado el paciente $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    } else {
        $_SESSION['mensaje_pacientes'] = "Tus datos no se han podido modificar.";
    }
}

  // Función para modificar paciente
  function modificar_paciente_cloudinary($con, $nombre, $apellido,  $usuario, $pass = null, $email, $foto = null, $busqueda) {
    unset($_SESSION['mensaje_paciente']);

    $query = "update paciente set  usuario = '$usuario' ,pass ='$pass' ,nombre = '$nombre', apellido = '$apellido', email = '$email'";

    if ($foto) {
        $query .= ", foto = '$foto'";
    }

    $query .= " where usuario = '$busqueda'";

    if (mysqli_query($con, $query)) {
        $_SESSION['mensaje_pacientes'] = "<h5 class='mensaje'>Se ha modificado el paciente $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    } else {
        $_SESSION['mensaje_pacientes'] = "<h5>Tus datos no se han podido modificar.</h5>";
    }
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


/*************************FUNCIONES DE NUTRICIONISTA.PHP********************************************** */
//función para ver ficha de paciente
function obtener_datos_nutricionista ($con){
    $usuario = $_SESSION['usuario'];
    $resultado = mysqli_query ($con, "select id_nutricionista FROM nutricionista WHERE usuario = '$usuario'");
    if ($row = mysqli_fetch_assoc($resultado)) {
        return $row['id_nutricionista'];
    } else {
        return null;
    }
}

//funcion que busca paciente por apellido
function buscar_paciente_nutricionista($con, $apellido) {
    $id = $_SESSION['id_nutricionista'];
    unset($_SESSION['mensaje_paciente']);
    $apellido = mysqli_real_escape_string($con, $apellido);
    $resultado = mysqli_query($con, "select distinct p.usuario, p.nombre, p.apellido, p.email 
     from paciente p join nutricionista n on p.id_nutricionista = n.id_nutricionista
     where p.apellido like '$apellido%' and n.id_nutricionista = '$id'");
    return $resultado;
}

//función que obtiene lista de recetas
function listar_recetas_usuario($con){
    $id_nutri = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta where id_nutricionista = '$id_nutri';");
    return $resultado;
}

//funcion para crear receta
function crear_receta_nutricionista($con, $nombre_receta, $ingredientes_receta, $calorias_receta){
    $id_nutri = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con, "insert into receta (nombre, ingredientes, calorias,id_nutricionista) values ('$nombre_receta','$ingredientes_receta', '$calorias_receta', '$id_nutri');");
    if (!$resultado) {
        unset($_SESSION['mensaje_receta']);
        echo "Error al crear la receta: " . mysqli_error($con);
    }
    
    $_SESSION['mensaje_receta'] = "<h5 class='mensaje'>Se ha creado la receta </h5><h5> con nombre : $nombre_receta .</h5>";
    }

  //función que obtiene lista de recetas por nutricionista
function listar_recetas_nutricionista($con){
    $id = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta where id_nutricionista = '$id';");
    return $resultado;
}

//función que obtiene la tabla con el calendario de recetas
function obtener_calendario($con , $paciente){
    $_SESSION['calendario'] = $paciente;
    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
    $id_paciente = $fila['id_paciente'];
    $resultado = mysqli_query($con,"SELECT m.dia_semana, m.comida, r.nombre AS receta_nombre 
            FROM menu_semanal m
            JOIN receta r ON m.id_receta = r.id_receta
            WHERE m.id_paciente = '$id_paciente' 
            ORDER BY FIELD(dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'),
                     FIELD(comida, 'Desayuno', 'Almuerzo', 'Cena');");
    $menu = [];
    while ($fila = $resultado->fetch_assoc()) {
        $menu[$fila['dia_semana']][$fila['comida']] = $fila['receta_nombre'];
    }
    return $menu;          
}

//función que añade receta al calendario
function crear_receta_calendario($con , $paciente , $dia , $receta , $comida){
    $id_nutricionista = $_SESSION['id_nutricionista'];
    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
    $id_paciente = $fila['id_paciente'];
    $consulta_receta = mysqli_query($con , "select id_receta from receta where nombre = '$receta' and id_nutricionista = '$id_nutricionista'");
    $fila = mysqli_fetch_assoc($consulta_receta);
    $id_receta = $fila['id_receta'];
    $resultado = mysqli_query($con, "insert into menu_semanal (id_paciente, id_nutricionista, id_receta, dia_semana, comida) 
    values ('$id_paciente', '$id_nutricionista', '$id_receta', '$dia', '$comida')
    ON DUPLICATE KEY UPDATE 
    id_nutricionista = VALUES(id_nutricionista),
    id_receta = VALUES(id_receta);");
    if (!$resultado) {
        unset($_SESSION['mensaje_calendario']);
        echo "Error al crear paciente: " . mysqli_error($con);
    }
    
    $_SESSION['mensaje_calendario'] = "<h5 class='mensaje'>Se ha asignado la receta $receta</h5><h5> para el $dia al $comida, </h5><h5> al paciente $paciente.</h5>";
}

//función que obtiene lista de pacientes por nutricionista
function obtener_pacientes_nutricionista($con){
    $id = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con,"select usuario , nombre , apellido , email from paciente where id_nutricionista ='$id' ");
    return $resultado;
}
 
  // Función para crear paciente para el nutricionista
function crear_paciente_nutri_cloudinary($con, $nombre, $apellido, $email, $usuario, $pass = null, $foto = null) {
    $id = $_SESSION['id_nutricionista'];
    unset($_SESSION['mensaje_pacientes']);
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $query = "insert into paciente (usuario, pass, nombre, apellido, email,foto,id_nutricionista)values 
    ('$usuario' , '$hash_pass' ,'$nombre', '$apellido', '$email', '$foto' , '$id')";

    if (mysqli_query($con, $query)) {
        $_SESSION['mensaje_pacientes'] = "<h5 class='mensaje'>Se ha creado el paciente $usuario </h5><h5> con nombre completo : $nombre $apellido </h5><h5> y email : $email.</h5>";
    } else {
        $_SESSION['mensaje_pacientes'] = "Tus datos no se han podido modificar.";
    }
}   

//función para modificar receta buscada por nutricionista
function modificar_receta_nutricionista($con, $nombre_receta, $ingredientes_receta , $calorias_receta , $nombre_busq){
    $id = $_SESSION['id_nutricionista'];
    $nombre = mysqli_real_escape_string($con, $nombre_receta);
    $ingredientes = mysqli_real_escape_string($con, $ingredientes_receta);
    $calorias = mysqli_real_escape_string($con, $calorias_receta);
    $nombre_busq = mysqli_real_escape_string($con, $nombre_busq);
    $actualizacion = mysqli_query($con, "update receta set nombre = '$nombre' , calorias = '$calorias' , ingredientes = '$ingredientes' where id_nutricionista = '$id' and nombre = '$nombre_busq';");
    if (!$actualizacion) {
        $_SESSION['mensaje_receta'] = "<h5 class='mensaje'>Error al modificar la receta: " . mysqli_error($con)."</h5>";
    } else {
        $_SESSION['menu'] = obtener_calendario($con, $paciente);
        $_SESSION['mensaje_receta'] = "<h5 class='mensaje'>La receta de $nombre </h5><h5> ha sido actualizada .</h5>";
    }
  }

  function eliminar_receta_nutricionista($con , $nombre){
    $id = $_SESSION['id_nutricionista'];
    $nombre = mysqli_real_escape_string($con , $nombre);
    $borrado = mysqli_query($con , "delete from receta where nombre = '$nombre' and id_nutricionista = '$id';");
    if (!$borrado) {
        $_SESSION['mensaje_receta'] = "<h5 class='mensaje'>Error al borrar la receta: " . mysqli_error($con)."</h5>";
    } else {
        $_SESSION['mensaje_receta'] = "<h5 class='mensaje'>La receta de $nombre </h5><h5> ha sido eliminada .</h5>";
    }
  }

 //funcion para obtener las citas del nutricionista
function obtener_tabla_citas_nutricionista($con ){
    $id = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con , "select distinct c.fecha , c.hora , p.usuario , p.nombre , p.apellido, p.email
     from citas c join paciente p on c.paciente = p.id_paciente where c.nutricionista = '$id';");
       return $resultado;
} 

//función para buscar pacientes
function listar_pacientes_nutricionista($con){
    $id = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con,"select usuario from paciente where id_nutricionista = '$id'");
    return $resultado;
  }

//funcion para crear cita
function crear_cita_nutricionista($con, $paciente, $fecha, $hora) {
    $id = $_SESSION['id_nutricionista'];
    $fecha = mysqli_real_escape_string($con, $fecha);
    $hora = mysqli_real_escape_string($con, $hora);
    $paciente = mysqli_real_escape_string($con, $paciente);
    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
        if (!$fila) {
            $_SESSION['mensaje_cita'] = "<h5 class='mensaje'>No se encontró ningún paciente con el usuario: $paciente.</h5>";
            return;
        }
        $id_paciente = $fila['id_paciente'];

    $resultado = mysqli_query($con,  "insert into citas (fecha, hora, paciente, nutricionista) 
            values ('$fecha', '$hora', '$id_paciente', '$id')");

     if (!$resultado) {
        $_SESSION['mensaje_cita'] = "<h5 class='mensaje'>Error al crear la cita: " . mysqli_error($con)."</h5>";
        return;
    }
    $_SESSION['mensaje_cita'] = "<h5 class='mensaje'>Se ha creado una cita el $fecha </h5><h5>a la $hora, con el paciente $paciente.</h5>";
}

//funcion para borrar una cita
function borrar_cita_nutricionista($con, $paciente, $fecha, $hora) {
    $fecha = mysqli_real_escape_string($con, $fecha);
    $hora = mysqli_real_escape_string($con, $hora);
    $paciente = mysqli_real_escape_string($con, $paciente);
    $id = $_SESSION['id_nutricionista'];

    $consulta_paciente = mysqli_query($con , "select id_paciente from paciente where usuario = '$paciente'");
    $fila = mysqli_fetch_assoc($consulta_paciente);
        if (!$fila) {
            echo "<h5 class='mensaje'>No se encontró ningún paciente con el usuario: $paciente.</h5>";
            return;
        }
        $id_paciente = $fila['id_paciente'];

    $resultado = mysqli_query($con, "delete from citas WHERE fecha = '$fecha' and hora = '$hora' and paciente = '$id_paciente'");
if (!$resultado) {
    $_SESSION['mensaje_cita'] = "<h5 class='mensaje'>Error al borrar la cita: " . mysqli_error($con)."</h5>";
} elseif (mysqli_affected_rows($con) === 0) {
    $_SESSION['mensaje_cita'] = "<h5 class='mensaje'>No se encontró ninguna cita con esos datos.</h5>";
} else {
    $_SESSION['mensaje_cita'] = "<h5 class='mensaje'>Se ha eliminado la cita del $fecha a las $hora, con el paciente $paciente.</h5>";
    }
}
/************************************************************************************************ */
/*************************FUNCIONES DE PACIENTE.PHP********************************************** */
// Función para obtener datos del paciente
function ver_datos_paciente($con, $usuario) {
    $query = "SELECT p.usuario, p.nombre, p.apellido, p.email, p.foto, p.id_nutricionista, n.nombre AS nombre_nutricionista
              FROM paciente p
              LEFT JOIN nutricionista n ON p.id_nutricionista = n.id_nutricionista
              WHERE p.usuario = '$usuario'";

    $resultado = mysqli_query($con, $query);
    return mysqli_fetch_assoc($resultado);
}


// Función para modificar datos del paciente
function modificar_datos_paciente($con, $nombre, $apellido, $email, $usuario, $pass = null, $foto = null) {
    unset($_SESSION['mensaje_modificar']);

    $query = "UPDATE paciente SET nombre = '$nombre', apellido = '$apellido', email = '$email'";

    if ($pass) {
        $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
        $query .= ", pass = '$hash_pass'";
    }

    if ($foto) {
        $query .= ", foto = '$foto'";
    }

    $query .= " WHERE usuario = '$usuario'";

    if (mysqli_query($con, $query)) {
        $_SESSION['mensaje_modificar'] = "Tus datos se han modificado correctamente.";
    } else {
        $_SESSION['mensaje_modificar'] = "Tus datos no se han podido modificar.";
    }
}




// Función para introducir medidas corporales
function introducir_medidas($con, $usuario, $fecha, $altura, $peso, $grasa, $musculo) {
    unset($_SESSION['mensaje_medidas']);

    // Escapar variables
    $usuario = mysqli_real_escape_string($con, $usuario);
    $fecha = mysqli_real_escape_string($con, $fecha);
    $altura = mysqli_real_escape_string($con, $altura);
    $peso = mysqli_real_escape_string($con, $peso);
    $grasa = mysqli_real_escape_string($con, $grasa);
    $musculo = mysqli_real_escape_string($con, $musculo);

    // Verificar si se ejecuta dos veces
    echo "Insertando: Usuario: $usuario, Fecha: $fecha, Peso: $peso, Grasa: $grasa, Músculo: $musculo <br>";

    $query = "INSERT INTO medidas_paciente (id_paciente, fecha_registro, altura, peso, grasa_corporal, musculo) 
              SELECT id_paciente, '$fecha', '$altura', '$peso', '$grasa', '$musculo' 
              FROM paciente WHERE usuario = '$usuario'";

    if (mysqli_query($con, $query)) {
        $_SESSION['mensaje_modificar'] = "Tus datos se han introducido correctamente.";
    } else {
        $_SESSION['mensaje_modificar'] = "Error al insertar: " . mysqli_error($con);
    }
}





// Función para obtener medidas corporales
function obtener_medidas_paciente($con, $usuario) {
    $query = mysqli_query ($con, "SELECT id_progreso, fecha_registro, altura, peso, grasa_corporal, musculo, imc
        FROM medidas_paciente
        WHERE id_paciente = (SELECT id_paciente FROM paciente WHERE usuario = '$usuario')
        ORDER BY fecha_registro DESC
    ");
    
    $medidas = [];

    while ($fila = mysqli_fetch_assoc($query)) {
        $medidas[] = $fila;
    }

    return $medidas;
}


// Función para modificar medidas corporales
function modificar_medidas($con, $usuario, $altura, $peso, $grasa, $musculo) {
    mysqli_query($con, "UPDATE medidas_paciente SET altura = '$altura', peso = '$peso', grasa_corporal = '$grasa', musculo = '$musculo' 
        WHERE id_paciente = (SELECT id_paciente FROM paciente WHERE usuario = '$usuario') ORDER BY fecha_registro DESC LIMIT 1");
}

// Función para comparar medidas con los objetivos
function comparar_medidas_objetivos($con, $usuario) {
    $resultado = mysqli_query($con, "SELECT m.altura, m.peso, m.grasa_corporal, m.musculo, o.objetivo_peso, o.objetivo_grasa_corporal, o.objetivo_musculo 
        FROM medidas_paciente m JOIN objetivos_paciente o ON m.id_paciente = o.id_paciente 
        WHERE m.id_paciente = (SELECT id_paciente FROM paciente WHERE usuario = '$usuario') ORDER BY m.fecha_registro DESC LIMIT 1");
    return mysqli_fetch_assoc($resultado);
}

// Función para mostrar el menú semanal
function mostrar_menu_semanal($con, $usuario) {
    $query = "SELECT ms.dia_semana, ms.comida, r.nombre AS plato, r.ingredientes, r.calorias
        FROM menu_semanal ms JOIN receta r ON ms.id_receta = r.id_receta JOIN paciente p ON ms.id_paciente = p.id_paciente
        WHERE p.usuario = '$usuario'";

    $result = $con->query($query);
    $menu_semanal = [];

    while ($row = $result->fetch_assoc()) {
        $menu_semanal[] = $row;
    }

    return $menu_semanal;
}


// Funciones para gestionar citas desde el paciente
function mostrar_citas_paciente($con, $usuario) {
    $id_paciente_query = mysqli_query($con, "SELECT id_paciente FROM paciente WHERE usuario = '$usuario'");
    $id_paciente = mysqli_fetch_assoc($id_paciente_query)['id_paciente'];
    
    $citas_query = mysqli_query($con, "SELECT fecha, hora FROM citas WHERE paciente = '$id_paciente'");
    
    $citas = [];
    while ($fila = mysqli_fetch_assoc($citas_query)) {
        $citas[] = $fila;
    }

    return $citas;
}


function crear_cita_paciente($con, $usuario, $fecha, $hora) {
    unset($_SESSION['mensaje_cita']);
    $id_paciente_query = mysqli_query($con, "SELECT id_paciente, id_nutricionista FROM paciente WHERE usuario = '$usuario'");
    $datos_paciente = mysqli_fetch_assoc($id_paciente_query);
    if (!$datos_paciente || !$datos_paciente['id_nutricionista']) {
        $_SESSION['mensaje_cita'] = "No se encontró un nutricionista asignado.";
        return;
    }
    $id_paciente = $datos_paciente['id_paciente'];
    $id_nutricionista = $datos_paciente['id_nutricionista'];
    mysqli_query($con, "INSERT INTO citas (fecha, hora, paciente, nutricionista) VALUES ('$fecha', '$hora', '$id_paciente', '$id_nutricionista')");
}

function modificar_cita_paciente($con, $usuario, $fecha, $hora) {
    $id_paciente_query = mysqli_query($con, "SELECT id_paciente FROM paciente WHERE usuario = '$usuario'");
    $id_paciente = mysqli_fetch_assoc($id_paciente_query)['id_paciente'];
    mysqli_query($con, "UPDATE citas SET fecha = '$fecha', hora = '$hora' WHERE paciente = '$id_paciente'");
}

function borrar_cita_paciente($con, $usuario, $fecha, $hora) {
    $id_paciente_query = mysqli_query($con, "SELECT id_paciente FROM paciente WHERE usuario = '$usuario'");
    $id_paciente = mysqli_fetch_assoc($id_paciente_query)['id_paciente'];
    mysqli_query($con, "DELETE FROM citas WHERE paciente = '$id_paciente' AND fecha = '$fecha' AND hora = '$hora'");
}




/**************************************************************************************************************************/
?>

