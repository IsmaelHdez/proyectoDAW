<?php
require("conexion.php");
$con = conexion();

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SESSION["tipo"] != 3){
    header("Location: index.php");
    }
    

//formulario para crear nutricionista
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["crear_nutricionista"])) {
    $nuevo_usuario = $_POST["usuario_nutricionista"];
    $nuevo_nombre = $_POST["nombre_nutricionista"];
    $nuevo_apellido = $_POST["apellido_nutricionista"];
    $nuevo_email = $_POST["email_nutricionista"];
    $nueva_pass = $_POST["pass_nutricionista"];
    
    if(empty($_FILES["foto_nutricionista"]["tmp_name"])){
        $_SESSION['mensaje_nutricionista'] = "<h5>Debe incluir una foto para crear al nutricionista.</h5>";
        header('Location:admin.php#div_nutricionista');
        exit;
    }
    $nueva_foto = subir_imagen_cloudinary($_FILES["foto_nutricionista"]["tmp_name"]);
    crear_nutricionista_cloudinary($con, $nuevo_nombre, $nuevo_apellido, $nuevo_email, $nuevo_usuario, $nueva_pass, $nueva_foto);
    header('Location:admin.php#div_nutricionista');
    exit;
}

//formulario para modificar un nutricionista
if (isset($_POST['nutricionista_mod'])) {
    if (!empty($_POST['usuario_nutricionista_mod']) && !empty($_POST['pass_nutricionista_mod']) 
    && !empty($_POST['nombre_nutricionista_mod']) && !empty($_POST['apellido_nutricionista_mod']) 
&& !empty($_POST['email_nutricionista_mod']) && !empty($_POST['busq_nutricionista'])) {
        $busq_nutricionista = $_POST['busq_nutricionista'];
        $usuario_nutricionista_mod = $_POST['usuario_nutricionista_mod'];
        $pass_nutricionista_mod = !empty($_POST["pass_nutricionista_mod"]) ? password_hash($_POST["pass_nutricionista_mod"], PASSWORD_DEFAULT) : null;
        $nombre_nutricionista_mod = $_POST['nombre_nutricionista_mod'];
        $apellido_nutricionista_mod = $_POST['apellido_nutricionista_mod'];
        $email_nutricionista_mod = $_POST['email_nutricionista_mod'];
        $nueva_foto = null;

        if (isset($_FILES["foto_nutricionista_mod"]) && $_FILES["foto_nutricionista_mod"]["size"] > 0) {
            // Buscar la foto actual en la base de datos
            $resultado = mysqli_query($con, "SELECT foto FROM nutricionista WHERE usuario = '$busq_nutricionista'");
            
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                
                // Eliminar la foto solo si existe
                if (!empty($fila['foto'])) {
                    eliminar_imagen_cloudinary($fila['foto']);
                }
            }
            
            // Subir nueva foto
            $nueva_foto = subir_imagen_cloudinary($_FILES["foto_nutricionista_mod"]["tmp_name"]);
        }
        $foto_actualizada = $nueva_foto ? $nueva_foto : $fila['foto']; 
        $resultado = modificar_nutricionista_cloudinary($con, $nombre_nutricionista_mod , $apellido_nutricionista_mod ,  $email_nutricionista_mod, $usuario_nutricionista_mod , $pass_nutricionista_mod  , $foto_actualizada ,$busq_nutricionista);
        header('Location:admin.php#div_nutricionista');
        exit;
    } 
}

//formulario para eliminar un nutricionista
if (isset($_POST['eliminar_nutricionista'])) {
    if (!empty($_POST['borrar_nutricionista'])) {
        $usuario = $_POST['borrar_nutricionista'];
        $resultado = eliminar_nutricionista($con, $usuario);
        header('Location:admin.php#div_nutricionista');
        exit;
    } 
}


//formulario para crear paciente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["crear_paciente"])) {
    $nuevo_usuario = $_POST["usuario_paciente"];
    $nuevo_nombre = $_POST["nombre_paciente"];
    $nuevo_apellido = $_POST["apellido_paciente"];
    $nuevo_email = $_POST["email_paciente"];
    $nueva_pass = $_POST["pass_paciente"];
    
    if(empty($_FILES["foto_paciente"]["tmp_name"])){
        $_SESSION['mensaje_pacientes'] = "<h5>Debe incluir una foto para crear al paciente.</h5>";
        header('Location:admin.php#div_pacientes');
        exit;
    }
    $nueva_foto = subir_imagen_cloudinary($_FILES["foto_paciente"]["tmp_name"]);
    crear_paciente_cloudinary($con, $nuevo_nombre, $nuevo_apellido, $nuevo_email, $nuevo_usuario, $nueva_pass, $nueva_foto);
    header('Location:admin.php#div_pacientes');
    exit;
}

//formulario para modificar un paciente
if (isset($_POST['paciente_mod'])) {
    if (!empty($_POST['usuario_paciente_mod']) && !empty($_POST['pass_paciente_mod']) 
    && !empty($_POST['nombre_paciente_mod']) && !empty($_POST['apellido_paciente_mod']) 
&& !empty($_POST['email_paciente_mod']) && !empty($_POST['busq_paciente'])) {
    $busq_paciente = $_POST['busq_paciente'];
    $usuario_paciente_mod = $_POST['usuario_paciente_mod'];
    $pass_paciente_mod = !empty($_POST["pass_paciente_mod"]) ? password_hash($_POST["pass_paciente_mod"], PASSWORD_DEFAULT) : null;
    $nombre_paciente_mod = $_POST['nombre_paciente_mod'];
    $apellido_paciente_mod = $_POST['apellido_paciente_mod'];
    $email_paciente_mod = $_POST['email_paciente_mod'];
    $nueva_foto = null;

    if (isset($_FILES["foto_paciente_mod"]) && $_FILES["foto_paciente_mod"]["size"] > 0) {
        // Buscar la foto actual en la base de datos
        $resultado = mysqli_query($con, "SELECT foto FROM paciente WHERE usuario = '$busq_paciente'");
        
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $fila = mysqli_fetch_assoc($resultado);
            
            // Eliminar la foto solo si existe
            if (!empty($fila['foto'])) {
                eliminar_imagen_cloudinary($fila['foto']);
            }
        }
        
        // Subir nueva foto
        $nueva_foto = subir_imagen_cloudinary($_FILES["foto_paciente_mod"]["tmp_name"]);
    }
    $foto_actualizada = $nueva_foto ? $nueva_foto : $fila['foto']; 
    $resultado = modificar_paciente_cloudinary($con, $nombre_paciente_mod , $apellido_paciente_mod , $usuario_paciente_mod , $pass_paciente_mod ,  $email_paciente_mod , $foto_actualizada , $busq_paciente);
    header('Location:admin.php#div_pacientes');
    exit;
    } 
}


//formulario para eliminar paciente
if (isset($_POST['eliminar_paciente'])) {
    if (!empty($_POST['borrar_paciente'])) {
        $usuario = $_POST['borrar_paciente'];
        $resultado = eliminar_paciente($con, $usuario);
        header('Location:admin.php#div_pacientes');
        exit;
    } 
}


//tabla con los nutricionistas
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../CSS/admin.css">
<script src="../js/logout.js" defer></script>
<script src="../js/validacion_admin.js" defer></script>
<title>Document</title>
</head>
    <nav class="menu-lateral">
        <button onclick="mostrarSeccion('div_nutricionista')">Nutricionistas</button>
        <button onclick="mostrarSeccion('div_pacientes')">Pacientes</button>
    </nav>
        <div id="div_nutricionista" class="seccion">
        <body><h2>Listado de clientes/nutricionistas</h2>
<?php
    $resultado = obtener_nutricionistas($con);
    if(mysqli_num_rows($resultado)==0){
        echo '<h5 class="mensaje">No se encuentran usuarios.</h5>';
    }
    else{
        echo "<table>
        <tr><th>Usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th></tr>";
        while($fila = mysqli_fetch_array($resultado)){
            extract($fila);
            echo "<tr><td>$usuario</td><td>$nombre</td><td>$apellido</td><td>$email</td></tr>";
        }
        echo "</table>";
    }
    if(isset($_SESSION['mensaje_nutricionista'])){
        echo $_SESSION['mensaje_nutricionista'];
    }
    
    //Buscar nutricionistas
    echo '<div id="sub_nutricionista">
<form action="admin.php#sub_nutricionista" method="POST">
<h2>Buscador de nutricionistas por apellidos</h2>
<h3>Introduzca el apellido completo o la inicial</h3>
<input type="text" name="nutricionista_apellido" id="nutricionista_apellido" required><br/>
<input type="submit" name="buscar_nutricionista" value="Buscar nutricionista">

</form>
</div>';
//formulario para buscar nutricionista por apellido
if (isset($_POST['buscar_nutricionista'])) {
    if (!empty($_POST['nutricionista_apellido'])) {
        $busqueda = mysqli_real_escape_string($con, trim($_POST['nutricionista_apellido']));
        $resultado = buscar_nutricionista($con, $busqueda);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                echo "<p><b>Apellido: </b>" . htmlspecialchars($fila['apellido']) . "</p>";
                echo "<p><b>Nombre: </b>" . htmlspecialchars($fila['nombre']) . "</p>";
                echo "<p><b>Email: </b>" . htmlspecialchars($fila['email']) . "</p>";
                echo "<p><b>Usuario: </b>" . htmlspecialchars($fila['usuario']) . "</p>";

            }
        } else {
            echo "<h5 class='mensaje'>No se encontraron resultados para '$busqueda'.</h5>";
        }
    } else {
        echo '<h5 class="mensaje">Por favor, ingrese un apellido para buscar.</h5>';
    }
}
        

//crear nutricionista
echo '<div id="crear_nutricionista">
    <form id="formulario_crear_nutricionista" action="admin.php#crear_nutricionista" method="POST" enctype="multipart/form-data">
        <h2>Creación de nutricionistas</h2>
        <label for="usuario_nutricionista">Usuario :</label>
        <input type="text" name="usuario_nutricionista" id="usuario_nutricionista" required><br/>
        
        <label for="pass_nutricionista">Contraseña :</label>
        <input type="password" name="pass_nutricionista" id="pass_nutricionista" required><br/>
        
        <label for="nombre_nutricionista">Nombre :</label>
        <input type="text" name="nombre_nutricionista" id="nombre_nutricionista" required><br/>
        
        <label for="apellido_nutricionista">Apellido :</label>
        <input type="text" name="apellido_nutricionista" id="apellido_nutricionista" required><br/>
        
        <label for="email_nutricionista">Email :</label>
        <input type="email" name="email_nutricionista" id="email_nutricionista" required><br/>
        
        <label for="foto_nutricionista">Foto de perfil:</label>
        <input type="file" name="foto_nutricionista" id="foto_nutricionista" accept="image/*">

        <input type="submit" name="crear_nutricionista" value="Crear nutricionista">
    </form>
    <div id="mensaje_error_crear_nutricionista" style="color: red; display: none;"></div>
        </div>';


//Modificar nutricionista
echo '<div id="modificar_nutricionista">
   <form id="formulario_mod_nutricionista" action="admin.php#div_nutricionista" method="POST" enctype="multipart/form-data">
   <h2>Modificación de nutricionistas</h2>
       </select><br/>
       <label for="busq_nutricionista">Elija el nutricionista :</label>
       <select name="busq_nutricionista" id="busq_nutricionista">';
        $resultado = listar_nutricionista($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $nutricionista = $fila['usuario'];
                echo "<option value='$nutricionista'>$nutricionista</option>";
            }
        } 
    echo '</select>
          <h3>Vuelva a introducir los datos del usuario : </h3>
          <label for="usuario_nutricionista_mod">Usuario :</label>
          <input type="text" name="usuario_nutricionista_mod" id="usuario_nutricionista_mod" required><br/>
          <label for="pass_nutricionista_mod">Contraseña :</label>
          <input type="password" name="pass_nutricionista_mod" id="pass_nutricionista_mod" required><br/>
          <label for="nombre_nutricionista_mod">Nombre :</label>
          <input type="text" name="nombre_nutricionista_mod" id="nombre_nutricionista_mod" required><br/>
          <label for="apellido_nutricionista_mod">Apellido :</label>
          <input type="text" name="apellido_nutricionista_mod" id="apellido_nutricionista_mod" required><br/>
          <label for="email_nutricionista_mod">Email :</label>
          <input type="email" name="email_nutricionista_mod" id="email_nutricionista_mod" required><br/>
          <label for="foto_nutricionista_mod">Foto de perfil:</label>
        <input type="file" name="foto_nutricionista_mod" id="foto_nutricionista_mod" accept="image/*">
          <input type="submit" name="nutricionista_mod" value="Modificar nutricionista">
      </form>
      <div id="mensaje_error_mod_nutricionista" style="color: red; display: none;"></div>
      </div>';
   
   
//Eliminar nutricionista
echo '<div id="borrar_nutri">
      <form action="admin.php#div_nutricionista" method="POST">
      <h2>Eliminación de nutricionistas</h2>
      </select><br/>
       <label for="borrar_nutricionista">Elija el nutricionista :</label>
       <select name="borrar_nutricionista" id="borrar_nutricionista">';
        $resultado = listar_nutricionista($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $nutricionista = $fila['usuario'];
                echo "<option value='$nutricionista'>$nutricionista</option>";
            }
        } 
    echo '</select>
      <input type="submit" name="eliminar_nutricionista" value="Eliminar nutricionista">
      </form>
      </div>
      </div>';
      
      
//Tabla con los pacientes
    echo '<div id="div_pacientes" class="seccion">
    <h2>Listado de pacientes</h2>';
    $resultado = obtener_pacientes($con);
      if(mysqli_num_rows($resultado)==0){
        echo "<h2>No se encuentran usuarios.</h2>";
    }else{
        echo "<table>
        <tr><th>Usuario</th><th>Nombre</th><th>Apellido</th><th>Email</th></tr>";
        while($fila = mysqli_fetch_array($resultado)){
            extract($fila);
            echo "<tr><td>$usuario</td><td>$nombre</td><td>$apellido</td><td>$email</td></tr>";
        }
        echo "</table>";
    }
    if(isset($_SESSION['mensaje_pacientes'])){
        echo $_SESSION['mensaje_pacientes'];
    }
    
    //Buscador pacientes
    echo '<div id="buscador_paciente">
    <form action="admin.php#buscador_paciente" method="POST">
    <h2>Buscador de paciente por apellido</h2>
    <h3>Introduzca el apellido completo o la inicial</h3>
    <input type="text" name="apellido_paciente_buscar" id="apellido_paciente_buscar" required><br/>
    <input type="submit" name="buscar_paciente">
    </form>
    </div>';
    if (isset($_POST['buscar_paciente'])) {
        if (!empty($_POST['apellido_paciente_buscar'])) {
            $busqueda = mysqli_real_escape_string($con, trim($_POST['apellido_paciente_buscar']));
            $resultado = buscar_paciente($con, $busqueda);
            
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<p><b>Apellido: </b>" . htmlspecialchars($fila['apellido']) . "</p>";
                    echo "<p><b>Nombre: </b>" . htmlspecialchars($fila['nombre']) . "</p>";
                    echo "<p><b>Email: </b>" . htmlspecialchars($fila['email']) . "</p>";
                    echo "<p><b>Usuario: </b>" . htmlspecialchars($fila['usuario']) . "</p>";
                }
            } else {
                echo "<h5 class='mensaje'>No se encontraron resultados para '$busqueda'.</h5>";
            }
        } else {
            echo '<h5 class="mensaje">Por favor, ingrese un apellido para buscar.</h5>';
        }
    } 
    


//crear paciente
echo '<div id="crear_paciente">
        <form id="formulario_crear_paciente" action="admin.php#div_pacientes" method="POST" enctype="multipart/form-data">
            <h2>Creación de pacientes</h2>
            <label for="usuario_paciente">Usuario :</label>
            <input type="text" name="usuario_paciente" id="usuario_paciente" required><br/>
            <label for="pass_paciente">Contraseña :</label>
            <input type="password" name="pass_paciente" id="pass_paciente" required><br/>
            <label for="nombre_paciente">Nombre :</label>
            <input type="text" name="nombre_paciente" id="nombre_paciente" required><br/>
            <label for="apellido_paciente">Apellido :</label>
            <input type="text" name="apellido_paciente" id="apellido_paciente" required><br/>
            <label for="email_paciente">Email :</label>
            <input type="email" name="email_paciente" id="email_paciente" required><br/>
            <label for="foto_paciente">Foto de perfil:</label>
            <input type="file" name="foto_paciente" id="foto_paciente" accept="image/*">
            <input type="submit" name="crear_paciente" value="Crear paciente">
        </form>
        <div id="mensaje_error_crear_paciente" style="color: red; display: none;"></div>
        </div>';




//Modificar paciente
  echo '<div id="modificar_paciente">
        <form id="formulario_mod_paciente" action="admin.php#div_pacientes" method="POST" enctype="multipart/form-data">
            <h2>Modificación de pacientes</h2>
            <label for="busq_paciente">Elija un paciente para asignar la receta anterior:</label>
            <select name="busq_paciente" id="busq_paciente">';
              $resultado = listar_pacientes($con);
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                 while ($fila = mysqli_fetch_assoc($resultado)) {
                 $paciente = $fila['usuario'];
                 echo "<option value='$paciente'>$paciente</option>";
                }
            }
      echo '</select>
            <h4>Vuelva a introducir los datos del paciente : </h4>
            <label for="usuario_paciente_mod">Usuario :</label>
            <input type="text" name="usuario_paciente_mod" id="usuario_paciente_mod" required><br/>
            <label for="pass_paciente_mod">Contraseña :</label>
            <input type="password" name="pass_paciente_mod" id="pass_paciente_mod" required><br/>
            <label for="nombre_paciente_mod">Nombre :</label>
            <input type="text" name="nombre_paciente_mod" id="nombre_paciente_mod" required><br/>
            <label for="apellido_paciente_mod">Apellido :</label>
            <input type="text" name="apellido_paciente_mod" id="apellido_paciente_mod" required><br/>
            <label for="email_paciente_mod">Email :</label>
            <input type="email" name="email_paciente_mod" id="email_paciente_mod" required><br/>
            <label for="foto_paciente_mod">Foto de perfil:</label>
            <input type="file" name="foto_paciente_mod" id="foto_paciente_mod" accept="image/*">
            <input type="submit" name="paciente_mod" value="Modificar paciente">
        </form>
        <div id="mensaje_error_mod_paciente" style="color: red; display: none;"></div>
        </div>';

//Eliminar paciente
echo '<div id="borrar_paci">
        <form action="admin.php#div_pacientes" method="POST">
            <h3>Eliminación de pacientes</h3>
            <label for="borrar_paciente">Elija un paciente para eliminar:</label>
            <select name="borrar_paciente" id="borrar_paciente">';
              $resultado = listar_pacientes($con);
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                 while ($fila = mysqli_fetch_assoc($resultado)) {
                 $paciente = $fila['usuario'];
                 echo "<option value='$paciente'>$paciente</option>";
                }
            }
      echo '</select>
            <input type="submit" name="eliminar_paciente" value="Eliminar paciente">
        </form>
        </div>
        </div>';

echo        '<div id="boton_logout">   
        <button id="cerrarSesion">Cerrar sesión</button>
             </div>
            </body>
                </html>';
 
?>