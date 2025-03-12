<?php
require("conexion.php");
$con = conexion();

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*if($_SESSION["tipo"] != 3){
    header("Location: index.php");
    }*/
    

    //formulario para crear nutricionista
if (isset($_POST['crear_nutricionista'])) {
    if (!empty($_POST['usuario_nutricionista']) && !empty($_POST['pass_nutricionista']) && !empty($_POST['nombre_nutricionista']) && !empty($_POST['apellido_nutricionista']) && !empty($_POST['email_nutricionista'])) {
        $usuario_nutricionista = $_POST['usuario_nutricionista'];
        $pass_nutricionista = $_POST['pass_nutricionista'];
        $nombre_nutricionista = $_POST['nombre_nutricionista'];
        $apellido_nutricionista = $_POST['apellido_nutricionista'];
        $email_nutricionista = $_POST['email_nutricionista'];
        $resultado = crear_nutricionista_admin($con, $nombre_nutricionista , $apellido_nutricionista , $usuario_nutricionista , $pass_nutricionista ,  $email_nutricionista);
        header('Location:admin.php#div_nutricionista');
        exit;

    } 
}
//formulario para modificar un nutricionista
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
if (isset($_POST['crear_paciente'])) {
    if (!empty($_POST['usuario_paciente']) && !empty($_POST['pass_paciente']) && !empty($_POST['nombre_paciente']) && !empty($_POST['apellido_paciente']) && !empty($_POST['email_paciente'])) {
        $usuario_paciente = $_POST['usuario_paciente'];
        $pass_paciente = $_POST['pass_paciente'];
        $nombre_paciente = $_POST['nombre_paciente'];
        $apellido_paciente = $_POST['apellido_paciente'];
        $email_paciente = $_POST['email_paciente'];
        $resultado = crear_paciente($con, $nombre_paciente , $apellido_paciente , $usuario_paciente , $pass_paciente ,  $email_paciente);
        header('Location:admin.php#div_pacientes');
        exit;
    } 
}

//formulario para modificar un paciente
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


//formulario para asociar paciente a nutricionista
if(isset($_POST['paciente']) && isset($_POST['nutricionista'])){
    if(!empty($_POST['paciente']) && !empty($_POST['nutricionista'])){
        $paciente = $_POST['paciente'];
      $nutricionista = $_POST['nutricionista'];
      asociar_paciente($con,$paciente,$nutricionista);
      header('Location:admin.php#asociar_nutricionista');
      exit;
   }else{
    $_SESSION['mensaje_pacientes'] = '<h5 class="mensaje">Debe asignar un paciente a un nutricionista.</h5>';
    }
}

//formulario para borrar una cita
if(isset($_POST['borrar_cita'])){
  if(!empty($_POST['paciente_cita_borrar']) && !empty($_POST['nutricionista_cita_borrar']) && !empty($_POST['borrar_hora_cita']) && !empty($_POST['borrar_fecha_cita'])){
      $paciente = $_POST['paciente_cita_borrar'];
      $nutricionista = $_POST['nutricionista_cita_borrar'];
      $fecha = $_POST['borrar_fecha_cita'];
      $hora = $_POST['borrar_hora_cita'];
      borrar_cita($con , $paciente , $nutricionista , $fecha , $hora);
  }else{
      echo '<h5 class="mensaje">Debe rellenar todos los datos.</h5>';
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
<script src="../JS/validacion_admin.js" defer></script>
<title>Document</title>
</head>
        <div id="div_nutricionista">
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
    <form id="formulario_crear_nutricionista" action="admin.php#crear_nutricionista" method="POST">
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
        
        <input type="submit" name="crear_nutricionista" value="Crear nutricionista">
    </form>
    <div id="mensaje_error_crear_nutricionista" style="color: red; display: none;"></div>
        </div>';


//Modificar nutricionista
echo '<div id="modificar_nutricionista">
   <form id="formulario_mod_nutricionista" action="admin.php#div_nutricionista" method="POST">
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
    echo '<div id="div_pacientes">
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
        <form id="formulario_crear_paciente" action="admin.php#div_pacientes" method="POST">
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
            <input type="submit" name="crear_paciente" value="Crear paciente">
        </form>
        <div id="mensaje_error_crear_paciente" style="color: red; display: none;"></div>
        </div>';

//Modificar paciente
  echo '<div id="modificar_paciente">
        <form id="formulario_mod_paciente" action="admin.php#div_pacientes" method="POST">
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

//Tabla de recetas
  echo '<div id="div_recetas">
        <h2>Recetas (ración/450 grs)</h2>';
    $resultado = listar_recetas($con);
    if(mysqli_num_rows($resultado)==0){
      echo "<p>No hay recetas disponibles.</p>";
    }else{
      echo "<table>
      <tr><th>Plato</th><th>calorias/racion</th><th>Ingredientes</th></tr>";
      while($fila = mysqli_fetch_array($resultado)){
          extract($fila);
          echo "<tr><td>$nombre</td><td>$calorias</td><td>$ingredientes</td></tr>";
      }
      echo "</table>";
  }
  echo "<hr>";

//Buscador receta
  echo '<div id="buscador_receta">
      <form action="admin.php#buscador_receta" method="POST">
          <h2>Buscador de receta por calorias</h2>
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
                echo "<div id='resultado_receta'><h3>***Receta***</h3>
                <h4>Receta : ".htmlspecialchars($fila['nombre'])."</h4>
                <h4>Calorias : ".htmlspecialchars($fila['calorias'])."</h4>
                <h4>Descripción : ".htmlspecialchars($fila['ingredientes'])."</h4></div>";
                
            }
        } else {
            echo '<h5 class="mensaje">No se encontraron recetas.</h5>';
        }
    } 
}
  
  //asociar paciente a nutricionista
  echo '<div id="asociar_nutricionista">
  <form action="admin.php#asociar_nutricionista" method="POST">
    <h2>Asigne un paciente a un nutricionista.</h2>
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
</form>';
if(isset($_SESSION['mensaje_asociar'])){
        echo $_SESSION['mensaje_asociar'];
    }
echo '</div>';

  //asociar receta a paciente
  echo '<div id="asociar_receta">
    <form action="admin.php#asociar_receta" method="POST">
      <h2>Asigne recetas a un paciente</h2>
      <label for="nombre_receta">Seleccione la receta</label>
      <select name="nombre_receta" id="receta">';
        $resultado = listar_recetas($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $receta = $fila['nombre'];
                echo "<option value='$receta'>$receta</option>";
            }
        } 
    echo '</select>
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
         <input type="submit" name="paciente_a_receta" value="Asociar">
</form>';
var_dump($_POST['nombre_receta']);
var_dump($_POST['paciente_nombre']);
//formulario para asignar la receta a un paciente
if(isset($_POST['paciente_a_receta'])){
    if(!empty($_POST['nombre_receta']) && !empty($_POST['paciente_nombre'])){
        $nombre_receta = $_POST['nombre_receta'];
        $paciente = $_POST['paciente_nombre'];
        asociar_receta($con, $nombre_receta, $paciente);
    } else {
        echo '<h5 class="mensaje">Error: Asegúrese de seleccionar un paciente y una receta.</h5>';
    }
}
if(isset($_SESSION['mensaje_asociar_receta'])){
    echo $_SESSION['mensaje_asociar_receta'];
}
  echo '</div>';
  
 
//tabla de citas por nutricionistas
    echo '</div>
    <div id="div_citas">
    <h2>Listado de citas por nutricionista</h2>
    <form action="admin.php#div_citas" method="POST">
    <label for="nutricionista_tabla_cita">Elija un nutricionista para ver sus citas :</label>
    <select name="nutricionista_tabla_cita" id="nutricionista_tabla_cita">';
      $resultado = obtener_tabla_citas($con);
      var_dump($resultado);
      if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $nutricionista_cita = $fila['usuario'];
            echo "<option value='$nutricionista_cita'>$nutricionista_cita</option>";
        } 
        }else{
            echo '<option value="" >No hay nutricionistas!</option>';
        }
        echo '</select><br/>
        <input type="submit" name="submit" value="Ver citas">
    </form>';
    if (isset($_POST['nutricionista_tabla_cita']) && !empty($_POST['nutricionista_tabla_cita'])) {
        $nutricionista_seleccionado = $_POST['nutricionista_tabla_cita'];
        $citas = obtener_citas_por_nutricionista($con, $nutricionista_seleccionado);
        
        if ($citas && mysqli_num_rows($citas) > 0) {
            echo "<h3>Citas de $nutricionista_seleccionado</h3>";
            echo "<table border='1'>
                    <tr><th>Paciente</th><th>Fecha</th><th>Hora</th>
                    </tr>";
            while ($fila = mysqli_fetch_assoc($citas)) {
                echo "<tr><td>{$fila['usuario']}</td><td>{$fila['fecha']}</td><td>{$fila['hora']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo '<h5 class="mensaje"> No hay citas para este nutricionista.</h5>';
        }
    }

//crear citas
echo '<div id="crear_cita">
  <form action="admin.php#crear_cita" method="POST">
    <h2>Creación de citas :</h2>
    <label for="paciente_cita">Asigne el paciente:</label>
    <select name="paciente_cita" id="paciente_cita">';
        $resultado = listar_pacientes($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $paciente = $fila['usuario'];
                echo "<option value='$paciente'>$paciente</option>";
            }
        } 
    echo '</select><br/>
    <label for="nutricionista_cita">Elija el nutricionista :</label>
    <select name="nutricionista_cita" id="nutricionista_cita">';
        $resultado = listar_nutricionista($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $nutricionista = $fila['usuario'];
                echo "<option value='$nutricionista'>$nutricionista</option>";
            }
        } 
    echo '</select><br/>';
    echo '<label for="hora_cita" name="hora_cita">Hora de la cita (hh:mm):</label>
         <input type="time" name="hora_cita"><br/>
         <label for="date" name="fecha_cita">Fecha de la cita (aaaa-mm-dd):</label>
         <input type="date" name="fecha_cita"><br/>
         <input type="submit" name="crear_cita" value="Crear cita">';

 //formulario para crear una cita
if(isset($_POST['crear_cita'])){
    if(!empty('paciente_cita') && !empty('nutricionista_cita') && !empty('hora_cita') && !empty('fecha_cita')){
        $paciente = $_POST['paciente_cita'];
        $nutricionista = $_POST['nutricionista_cita'];
        $fecha = $_POST['fecha_cita'];
        $hora = $_POST['hora_cita'];
        crear_cita($con , $paciente , $nutricionista , $fecha , $hora);
        echo "<h5 class='mensaje'>Creada cita del paciente $paciente con el nutricionista $nutricionista ,</h5><h5>para el dìa $fecha a las $hora .</h5>";
      }else{
          echo '<h5 class="mensaje">Debe rellenar todos los datos.</h5>';
      }
  }

//Borrar citas
echo '<div id="borrar_cita">
  <form action="admin.php#borrar_cita" method="POST">
    <h2>Cancelación de citas :</h2>
    <label for="nutricionista_cita_borrar">Elija el nutricionista:</label>
    <select name="nutricionista_cita_borrar" id="nutricionista_cita_borrar">';
        $resultado = listar_nutricionista($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $nutricionista = $fila['usuario'];
                echo "<option value='$nutricionista'>$nutricionista</option>";
            }
        } 
    echo '</select><br/>
    <label for="paciente_cita_borrar">Elija el paciente :</label>
    <select name="paciente_cita_borrar" id="paciente_cita_borrar">';
        $resultado = listar_pacientes($con);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $paciente = $fila['usuario'];
                echo "<option value='$paciente'>$paciente</option>";
            }
        } 
    echo '</select><br/>';
    echo '<label for="borrar_hora_cita" name="borrar_hora_cita">Hora de la cita (hh:mm):</label>
         <input type="time" name="borrar_hora_cita"><br/>
         <label for="date" name="borrar_fecha_cita">Fecha de la cita (aaaa-mm-dd):</label>
         <input type="date" name="borrar_fecha_cita"><br/>
         <input type="submit" name="borrar_cita" value="Borrar cita">';

        echo '</div>
               </body>
                </html>';
 
?>