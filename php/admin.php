<?php
require("conexion.php");

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*if($_SESSION["tipo"] != 3){
    header("Location: index.php");
}*/

//************************************************************************************************* */
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
//***************************************************************************************************/
//tabla con los nutricionistas
$con = conexion();
echo "<h2>Listado de clientes/nutricionistas</h2>";
$resultado = obtener_nutricionistas($con);
    if(mysqli_num_rows($resultado)==0){
        echo "<p>No se encuentran usuarios.</p>";
    }
    else{
        echo "<table border='1'>
        <tr><td>Usuario</td><td>Nombre</td><td>Apellido</td><td>Email</td></tr>";
        while($fila = mysqli_fetch_array($resultado)){
            extract($fila);
            echo "<tr><td>$usuario</td><td>$nombre</td><td>$apellido</td><td>$email</td></tr>";
        }
        echo "</table>";
    }
    echo "<hr>";

//Buscar nutricionistas
   echo '<div id="buscador_nutricionista">
        <form action="admin.php" method="POST">
            <h3>Buscador de nutricionistas por apellidos</h3>
            <h4>Introduzca el apellido completo o la inicial</h4>
            <input type="text" name="nutricionista_apellidp" id="nutricionista_apellido" required><br/>
            <input type="submit" name="buscar_nutricionista" value="Buscar nutricionista">

        </form>
        </div>';

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
                echo "<p>No se encontraron resultados para '$busqueda'.</p>";
            }
        } else {
            echo "<p>Por favor, ingrese un apellido para buscar.</p>";
        }
    }
//crear nutricionista
echo '<div id="crear_nutricionista">
        <form action="admin.php" method="POST">
            <h3>Creación de nutricionistas</h3>
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
            <input type="submit" name="crear_nutricionista" value="Crear nutricionista">
        </form>
        </div>';

    if (isset($_POST['crear_nutricionista'])) {
        if (!empty($_POST['usuario_nutricionista']) && !empty($_POST['pass_nutricionista']) && !empty($_POST['nombre_nutricionista']) && !empty($_POST['apellido_nutricionista']) && !empty($_POST['email_nutricionista'])) {
            $usuario_nutricionista = $_POST['usuario_nutricionista'];
            $pass_nutricionista = $_POST['pass_nutricionista'];
            $nombre_nutricionista = $_POST['nombre_nutricionista'];
            $apellido_nutricionista = $_POST['apellido_nutricionista'];
            $email_nutricionista = $_POST['email_nutricionista'];
            $resultado = crear_nutricionista($con, $nombre_nutricionista , $apellido_nutricionista , $usuario_nutricionista , $pass_nutricionista ,  $email_nutricionista);
            
        } 
    }

//Modificar nutricionista
echo '<div id="modificar_nutricionista">
        <form action="admin.php" method="POST">
            <h3>Modificación de nutricionistas</h3>
            <label for="text" name="busq_nutricionista" id="busq_nutricionista">Introduzca el usuario a modificar</label>
            <input type="text" name="busq_nutricionista" id="busq_nutricionista" required><br/>
            <h4>Vuelva a introducir los datos del usuario : </h4>
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
            <input type="submit" name="nutricionista_mod" value="Modificar nutricionista">
        </form>
        </div>';

    if (isset($_POST['nutricionista_mod'])) {
        if (!empty($_POST['usuario_nutricionista_mod']) && !empty($_POST['pass_nutricionista_mod']) 
        && !empty($_POST['nombre_nutricionista_mod']) && !empty($_POST['apellido_nutricionista_mod']) 
        && !empty($_POST['email_nutricionista_mod']) && !empty($_POST['busq_nutricionista'])) {
            $busq_nutricionista = $_POST['busq_nutricionista'];
            $usuario_nutricionista_mod = $_POST['usuario_nutricionista_mod'];
            $pass_nutricionista_mod = $_POST['pass_nutricionista_mod'];
            $nombre_nutricionista_mod = $_POST['nombre_nutricionista_mod'];
            $apellido_nutricionista_mod = $_POST['apellido_nutricionista_mod'];
            $email_nutricionista_mod = $_POST['email_nutricionista_mod'];
            $resultado = modificar_nutricionista($con, $nombre_nutricionista_mod , $apellido_nutricionista_mod , $usuario_nutricionista_mod , $pass_nutricionista_mod ,  $email_nutricionista_mod , $busq_nutricionista);
            
        } 
    }

//Eliminar nutricionista
echo '<div id="borrar_nutricionista">
        <form action="admin.php" method="POST">
            <h3>Eliminación de nutricionistas</h3>
            <label for="text" name="borrar_nutricionista" id="borrar_nutricionista">Introduzca el usuario a eliminar</label>
            <input type="text" name="borrar_nutricionista" id="borrar_nutricionista" required><br/>
            <input type="submit" name="eliminar_nutricionista" value="Eliminar nutricionista">
        </form>
        </div>';
        if (isset($_POST['eliminar_nutricionista'])) {
            if (!empty($_POST['borrar_nutricionista'])) {
                $usuario = $_POST['borrar_nutricionista'];
                $resultado = eliminar_nutricionista($con, $usuario);
                
            } 
        }

//Tabla con los pacientes
    echo "<h2>Listado de pacientes</h2>";
    $resultado = obtener_pacientes($con);
      if(mysqli_num_rows($resultado)==0){
        echo "<p>No se encuentran usuarios.</p>";
      }else{
        echo "<table border='1'>
        <tr><td>Usuario</td><td>Nombre</td><td>Apellido</td><td>Email</td></tr>";
        while($fila = mysqli_fetch_array($resultado)){
            extract($fila);
            echo "<tr><td>$usuario</td><td>$nombre</td><td>$apellido</td><td>$email</td></tr>";
        }
        echo "</table>";
    }
    echo "<hr>";

//Buscador pacientes
    echo '<div id="buscador_paciente">
        <form action="admin.php" method="POST">
            <h3>Buscador de paciente por apellido</h3>
            <h4>Introduzca el apellido completo o la inicial</h4>
            <input type="text" name="apellido_paciente" id="apellido_paciente" required><br/>
            <input type="submit" name="buscar_paciente">
        </form>
    </div>';

    if (isset($_POST['buscar_paciente'])) {
        if (!empty($_POST['apellido_paciente'])) {
            $busqueda = mysqli_real_escape_string($con, trim($_POST['apellido']));
            $resultado = buscar_paciente($con, $busqueda);
    
            if ($resultado && mysqli_num_rows($resultado) > 0) {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    echo "<p><b>Apellido: </b>" . htmlspecialchars($fila['apellido']) . "</p>";
                    echo "<p><b>Nombre: </b>" . htmlspecialchars($fila['nombre']) . "</p>";
                    echo "<p><b>Email: </b>" . htmlspecialchars($fila['email']) . "</p>";
                    echo "<p><b>Usuario: </b>" . htmlspecialchars($fila['usuario']) . "</p>";
                }
            } else {
                echo "<p>No se encontraron resultados para '$busqueda'.</p>";
            }
        } else {
            echo "<p>Por favor, ingrese un apellido para buscar.</p>";
        }
    } 


//crear paciente
echo '<div id="crear_paciente">
        <form action="admin.php" method="POST">
            <h3>Creación de pacientes</h3>
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
            <input type="submit" name="crear_paciente">
        </form>
        </div>';

    if (isset($_POST['crear_paciente'])) {
        if (!empty($_POST['usuario_paciente']) && !empty($_POST['pass_paciente']) && !empty($_POST['nombre_paciente']) && !empty($_POST['apellido_paciente']) && !empty($_POST['email_paciente'])) {
            $usuario_paciente = $_POST['usuario_paciente'];
            $pass_paciente = $_POST['pass_paciente'];
            $nombre_paciente = $_POST['nombre_paciente'];
            $apellido_paciente = $_POST['apellido_paciente'];
            $email_paciente = $_POST['email_paciente'];
            $resultado = crear_paciente($con, $nombre_paciente , $apellido_paciente , $usuario_paciente , $pass_paciente ,  $email_paciente);
            
        } 
    }
//Modificar paciente
echo '<div id="modificar_paciente">
        <form action="admin.php" method="POST">
            <h3>Modificación de pacientes</h3>
            <label for="text" name="busq_paciente" id="busq_paciente">Introduzca el paciente a modificar</label>
            <input type="text" name="busq_paciente" id="busq_paciente" required><br/>
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
            <input type="submit" name="paciente_mod" value="Modificar paciente">
        </form>
        </div>';

    if (isset($_POST['paciente_mod'])) {
        if (!empty($_POST['usuario_paciente_mod']) && !empty($_POST['pass_paciente_mod']) 
        && !empty($_POST['nombre_paciente_mod']) && !empty($_POST['apellido_paciente_mod']) 
        && !empty($_POST['email_paciente_mod']) && !empty($_POST['busq_paciente'])) {
            $busq_paciente = $_POST['busq_paciente'];
            $usuario_paciente_mod = $_POST['usuario_paciente_mod'];
            $pass_paciente_mod = $_POST['pass_paciente_mod'];
            $nombre_paciente_mod = $_POST['nombre_paciente_mod'];
            $apellido_paciente_mod = $_POST['apellido_paciente_mod'];
            $email_paciente_mod = $_POST['email_paciente_mod'];
            $resultado = modificar_paciente($con, $nombre_paciente_mod , $apellido_paciente_mod , $usuario_paciente_mod , $pass_paciente_mod ,  $email_paciente_mod , $busq_paciente);
            
        } 
    }
//Eliminar paciente
echo '<div id="borrar_paciente">
        <form action="admin.php" method="POST">
            <h3>Eliminación de pacientes</h3>
            <label for="text" name="borrar_paciente" id="borrar_paciente">Introduzca el usuario a eliminar</label>
            <input type="text" name="borrar_paciente" id="borrar_paciente" required><br/>
            <input type="submit" name="eliminar_paciente" value="Eliminar paciente">
        </form>
        </div>';
        if (isset($_POST['eliminar_paciente'])) {
            if (!empty($_POST['borrar_paciente'])) {
                $usuario = $_POST['borrar_paciente'];
                $resultado = eliminar_paciente($con, $usuario);
                
            } 
        }
//Tabla de recetas
  echo "<h2>Recetas (ración/450 grs)</h2>";
  $resultado = listar_recetas($con);
    if(mysqli_num_rows($resultado)==0){
      echo "<p>No hay recetas disponibles.</p>";
    }else{
      echo "<table border='1'>
      <tr><td>Plato</td><td>calorias/racion</td><td>Ingredientes</td></tr>";
      while($fila = mysqli_fetch_array($resultado)){
          extract($fila);
          echo "<tr><td>$nombre</td><td>$calorias</td><td>$ingredientes</td><td></tr>";
      }
      echo "</table>";
  }
  echo "<hr>";

//Buscador receta
  echo '<div id="buscador_receta">
      <form action="admin.php" method="POST">
          <h3>Buscador de receta por calorias</h3>
          <label for="calorias">Filtrar por calorías:</label>
        <select name="calorias" id="calorias">
            <option value=1>Menos de 200 calorías</option>
            <option value=2>Menos de 350 calorías</option>
            <option value=3>Menos de 450 calorías</option>
            <option value=4>Más de 500 calorías</option>
        </select><br/>
          <input type="submit" name="buscar_receta">
      </form>
  </div>';

  if (isset($_POST['buscar_receta'])) {
      if (!empty($_POST['calorias'])) {
          $busqueda = mysqli_real_escape_string($con, trim($_POST['calorias']));
          $resultado = buscar_calorias($con, $busqueda);
  
          if ($resultado && mysqli_num_rows($resultado) > 0) {
              while ($fila = mysqli_fetch_assoc($resultado)) {
                  echo "<p><b>Receta : </b>" . htmlspecialchars($fila['nombre']) . "</p>";
                  echo "<p><b>Calorias : </b>" . htmlspecialchars($fila['calorias']) . "</p>";
                  echo "<p><b>Descripción: </b>" . htmlspecialchars($fila['ingredientes']) . "</p>";
              }
          } else {
              echo "<p>No se encontraron recetas.</p>";
          }
      } 
  }  
  
  //asociar paciente a nutricionista
  echo '<div id="asociar_nutricionista">
  <form action="admin.php" method="POST">
    <h3>Asigne un paciente a un nutricionista.</h3>
    <label for="paciente">Asigne el paciente:</label>
    <select name="paciente" id="paciente">';
        $resultado = listar_pacientes($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $paciente = $fila['usuario'];
                echo "<option value='$paciente'>$paciente</option>";
            }
        } 
    echo '</select>
    <label for="nutricionista">y el nutricionista a el que le es asignado:</label>
    <select name="nutricionista" id="nutricionista">';

        $resultado = listar_nutricionista($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $nutricionista = $fila['usuario'];
                echo "<option value='$nutricionista'>$nutricionista</option>";
            }
        } 
    echo '</select>
    <br/>
    <input type="submit" name="paciente_a_nutricionista" value="Asociar">
  </form>
</div>';
  if(isset($_POST['paciente']) && isset($_POST['nutricionista'])){
       if(!empty($_POST['paciente']) && !empty($_POST['nutricionista'])){
        $paciente = $_POST['paciente'];
        $nutricionista = $_POST['nutricionista'];
      asociar_paciente($con,$paciente,$nutricionista);
      var_dump($paciente);
      var_dump($nutricionista);
     }else{
     echo "Debe asignar un paciente a un nutricionista.";
     }
    }

  //asociar paciente a receta
  echo '<div id="asociar_receta">
    <form action="" method="POST">
      <h3>Asigne recetas a un paciente</h3>
      <label for="receta">Busque la receta por nombre o inicial</label>
      <input type="text" name="receta_nombre" id="receta_nombre" required>
      <input type="submit" name="buscar_receta" value="Buscar">
    </form>';
  
  if(isset($_POST['buscar_receta'])){
      if (!empty($_POST['receta_nombre'])) {
          $busqueda = mysqli_real_escape_string($con, trim($_POST['receta_nombre']));
          $resultado = buscar_nombre_receta($con, $busqueda);
  
          if ($resultado && mysqli_num_rows($resultado) > 0) {
              while ($fila = mysqli_fetch_assoc($resultado)) {
                  $nombre_receta = $fila['nombre'];
                  echo "<p><b>Nombre de la receta: </b>" . htmlspecialchars($nombre_receta) . "</p>";
              }
          } else {
              echo "<p>No se han encontrado recetas con '$busqueda'</p>";
          }
      } else {
          echo "<p>Debe introducir un nombre en el buscador.</p>";
      }
  }
  
  echo '<form action="" method="POST">
      <label for="paciente_nombre">Elija un paciente para asignar la receta anterior:</label>
      <select name="paciente_nombre" id="paciente_nombre">';
  
  $resultado = listar_pacientes($con);
  if ($resultado && mysqli_num_rows($resultado) > 0) {
      while ($fila = mysqli_fetch_assoc($resultado)) {
          $paciente = $fila['usuario'];
          echo "<option value='$paciente'>$paciente</option>";
      }
  }
  
  echo '</select>
      <input type="hidden" name="nombre_receta" value="' . ($nombre_receta ?? '') . '">
      <br/>
      <input type="submit" name="paciente_a_receta" value="Asociar">
    </form>
  </div>';
  
  if(isset($_POST['paciente_a_receta'])){
      if(!empty($_POST['nombre_receta']) && !empty($_POST['paciente_nombre'])){
          $nombre_receta = $_POST['nombre_receta'];
          $paciente = $_POST['paciente_nombre'];
          asociar_receta($con, $nombre_receta, $paciente);
      } else {
          echo "<p>Error: Asegúrese de seleccionar un paciente y una receta.</p>";
      }
  }
  

?>