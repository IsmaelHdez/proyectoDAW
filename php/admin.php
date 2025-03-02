<?php
require("conexion.php");

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SESSION["tipo"] != 3){
    header("Location: index.php");
}



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
        <form action="admin.php#buscador_nutricionista" method="POST">
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
        <form action="admin.php#crear_nutricionista" method="POST">
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
        <form action="admin.php#modificar_nutricionista" method="POST">
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
        <form action="admin.php#borrar_nutricionista" method="POST">
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
        <form action="admin.php#buscador_paciente" method="POST">
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
        <form action="admin.php#crear_paciente" method="POST">
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
        <form action="admin.php#modificar_paciente" method="POST">
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
        <form action="admin.php#borrar_paciente" method="POST">
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
      <form action="admin.php#buscador_receta" method="POST">
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
  <form action="admin.php#asociar_nutricionista" method="POST">
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
    <form action="admin.php#asociar_receta" method="POST">
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
 
//tabla de citas por nutricionistas
echo '<div id="tabla_citas">
  <h2>Listado de citas por nutricionista</h2>
  <form action="admin.php#tabla_citas" method="POST">
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
            echo "<p>No hay citas para este nutricionista.</p>";
        }
    }
    echo "</div>";

//crear citas
echo '<div id="crear_cita">
  <form action="admin.php#crear_cita" method="POST">
    <h3>Creación de citas :</h3>
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

        if(isset($_POST['crear_cita'])){
          if(!empty('paciente_cita') && !empty('nutricionista_cita') && !empty('hora_cita') && !empty('fecha_cita')){
              $paciente = $_POST['paciente_cita'];
              $nutricionista = $_POST['nutricionista_cita'];
              $fecha = $_POST['fecha_cita'];
              $hora = $_POST['hora_cita'];
              crear_cita($con , $paciente , $nutricionista , $fecha , $hora);
          }else{
            echo 'Debe rellenar todos los datos.';
          }
        }
//Borrar citas
echo '<div id="borrar_cita">
  <form action="admin.php#borrar_cita" method="POST">
    <h3>Cancelación de citas :</h3>
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

        if(isset($_POST['borrar_cita'])){
          if(!empty($_POST['paciente_cita_borrar']) && !empty($_POST['nutricionista_cita_borrar']) && !empty($_POST['borrar_hora_cita']) && !empty($_POST['borrar_fecha_cita'])){
              $paciente = $_POST['paciente_cita_borrar'];
              $nutricionista = $_POST['nutricionista_cita_borrar'];
              $fecha = $_POST['borrar_fecha_cita'];
              $hora = $_POST['borrar_hora_cita'];
              borrar_cita($con , $paciente , $nutricionista , $fecha , $hora);
          }else{
            echo 'Debe rellenar todos los datos.';
          }
        }

?>