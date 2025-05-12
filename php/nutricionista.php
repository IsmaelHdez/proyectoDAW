<?php

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("conexion.php");
require("header_alta.php");
require("footer.html");
$con = conexion();
$_SESSION['id_nutricionista'] =(int) obtener_datos_nutricionista($con);

if($_SESSION["tipo"] != 1){
    header("Location: index.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/logout.js" defer></script>
    <script src="../js/validacion_nutricionista.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../CSS/admin.css">
    <title>Nutrigo</title>
</head>
<body>
<?php
    $resultado = mostrar_panel_nutricionista($con);
    $fila = mysqli_fetch_assoc($resultado);
    echo "<button id='toggle-panel'>👤 {$fila['usuario']}</button>";
    echo "<div id='side-panel'>";
    echo "<button id='close-panel'>✖</button>";
    if($fila['foto'] == null){
        echo "<img src='https://res.cloudinary.com/dup8qzlzv/image/upload/v1744743726/sin_foto_hjvtev.jpg' alt='Foto de perfil' width='140' height='140'>";
    }else{
        echo "<img src='{$fila['foto']}' alt='Foto de perfil' width='140' height='140'>";
    }
    echo "<p><strong>Nombre:</strong> <span id='user-name'>{$fila['nombre']} {$fila['apellido']}</span></p>";
    echo "<p><strong>Email:</strong> <span id='user-email'>{$fila['email']}</span></p>";
?>
</div>
<nav class="menu-lateral">
    <div class="logo">
        <img src="https://res.cloudinary.com/dup8qzlzv/image/upload/v1742377568/logo_csilnx.png" alt="logo" >
    </div>
    <div class="menu-item">
        <button class="menu-btn" data-target="#submenu_pacientes">Pacientes</button>
        <ul id="submenu_pacientes" class="submenu">
            <li onclick="mostrarSeccion('buscador_paciente','contenedor_tabla_paciente')">Buscar por apellido</li>
            <li onclick="mostrarSeccion('crear_paciente','contenedor_tabla_paciente')">Creación</li>
            <li onclick="mostrarSeccion('modificar_paciente','contenedor_tabla_paciente')">Modificación</li>
            <li onclick="mostrarSeccion('borrar_paciente','contenedor_tabla_paciente')">Eliminación</li>
        </ul>
    </div>
    <div class="menu-item">
        <button class="menu-btn" data-target="#submenu_recetas">Recetas</button>
        <ul id="submenu_recetas" class="submenu">
            <li onclick="mostrarSeccion('crear_receta','tabla_contenedor_recetas')">Creación</li>
            <li onclick="mostrarSeccion('modificar_receta','tabla_contenedor_recetas')">Modificación</li>
            <li onclick="mostrarSeccion('borrar_receta','tabla_contenedor_recetas')">Eliminación</li>
        </ul>
    </div>
    <div class="menu-item">
        <button class="menu-btn" data-target="#submenu_calendario">Calendario</button>
        <ul id="submenu_calendario" class="submenu">
            <li onclick="mostrarSeccion('asignar_calendario','tabla_calendario')">Asignar receta al calendario</li>
        </ul>
    </div>
    <div class="menu-item">
        <button class="menu-btn" data-target="#submenu_citas">Citas</button>
        <ul id="submenu_citas" class="submenu">
            <li onclick="mostrarSeccion('crear_cita','tabla_citas')">Creación</li>
            <li onclick="mostrarSeccion('borrar_cita','tabla_citas')">Eliminación</li>
        </ul>
    </div>
</nav>
<?php


//formulario para crear paciente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["crear_paciente"])) {
    $nuevo_usuario = $_POST["usuario_paciente"];
    $nuevo_nombre = $_POST["nombre_paciente"];
    $nuevo_apellido = $_POST["apellido_paciente"];
    $nuevo_email = $_POST["email_paciente"];
    $nueva_pass = $_POST["pass_paciente"];
    
    if ($_FILES["foto_paciente"]["size"] > 0) {
        $nueva_foto = subir_imagen_cloudinary($_FILES["foto_paciente"]["tmp_name"]);
    }else{
        $nueva_foto = null;
    }
    crear_paciente_nutri_cloudinary($con, $nuevo_nombre, $nuevo_apellido, $nuevo_email, $nuevo_usuario, $nueva_pass, $nueva_foto);
    header('Location:nutricionista.php#div_pacientes');
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
    header('Location:nutricionista.php#div_pacientes');
    exit;
    } 
}

//formulario para eliminar paciente
if (isset($_POST['eliminar_paciente'])) {
    if (!empty($_POST['borrar_paciente'])) {
        $usuario = $_POST['borrar_paciente'];
        $resultado = eliminar_paciente($con, $usuario);
        header('Location:nutricionista.php#div_pacientes');
        exit;
    } 
}

//formulario para crear receta
    if (isset($_POST['crear_receta'])) {
        if (!empty($_POST['nombre_receta']) && !empty($_POST['ingredientes_receta']) && !empty($_POST['calorias_receta'])) {
            $nombre_receta = $_POST['nombre_receta'];
            $ingredientes_receta = $_POST['ingredientes_receta'];
            $calorias_receta = $_POST['calorias_receta'];
            $resultado = crear_receta_nutricionista($con, $nombre_receta, $ingredientes_receta , $calorias_receta );
            header('Location:nutricionista.php#div_recetas');
            exit;
    
        } 
    }

  //formulario para modificar receta
  if (isset($_POST['crear_receta_mod'])) {
    if (!empty($_POST['nombre_receta_mod']) && !empty($_POST['ingredientes_receta_mod']) && !empty($_POST['calorias_receta_mod'])) {
        $nombre_receta = $_POST['nombre_receta_mod'];
        $ingredientes_receta = $_POST['ingredientes_receta_mod'];
        $calorias_receta = $_POST['calorias_receta_mod'];
        $nombre_busq = $_POST['busq_receta'];
        $resultado = modificar_receta_nutricionista($con, $nombre_receta, $ingredientes_receta , $calorias_receta , $nombre_busq);
        header('Location:nutricionista.php#div_recetas');
        exit;

    } 
}  

//formulario para eliminar receta
if (isset($_POST['eliminar_receta'])) {
    if (!empty($_POST['borrar_receta'])) {
        $nombre = $_POST['borrar_receta'];
        $resultado = eliminar_receta_nutricionista($con, $nombre);
        header('Location:nutricionista.php#div_recetas');
        exit;
    } 
}

//formulario para crear una cita
if(isset($_POST['crear_cita'])){
    if(!empty('paciente_cita') && !empty('hora_cita') && !empty('fecha_cita')){
        $paciente = $_POST['paciente_cita'];
        $fecha = $_POST['fecha_cita'];
        $hora = $_POST['hora_cita'];
        crear_cita_nutricionista($con , $paciente , $fecha , $hora);
        header('Location:nutricionista.php#div_citas');
        exit;
      }else{
        $_SESSION['mensaje_cita'] = '<h5 class="mensaje">Debe rellenar todos los datos.</h5>';
      }
    }

//formulario para borrar una cita
if(isset($_POST['borrar_cita'])){
    if(!empty($_POST['paciente_cita_borrar']) && !empty($_POST['borrar_hora_cita']) && !empty($_POST['borrar_fecha_cita'])){
        $paciente = $_POST['paciente_cita_borrar'];
        $fecha = $_POST['borrar_fecha_cita'];
        $hora = $_POST['borrar_hora_cita'];
        borrar_cita_nutricionista($con , $paciente , $fecha , $hora);
    }else{
        $_SESSION['mensaje_cita'] ='<h5 class="mensaje">Debe rellenar todos los datos.</h5>';
    }
  }
//formulario para asignar receta al calendario
if(isset($_POST['asignar_calendario'])){
    if(!empty($_POST['asignar_paciente_calendario']) && !empty($_POST['asignar_dia_calendario'])
       && !empty($_POST['asignar_receta_calendario']) && !empty($_POST['asignar_comida_calendario'])){
        $paciente = $_POST['asignar_paciente_calendario'];
        $dia = $_POST['asignar_dia_calendario'];
        $receta = $_POST['asignar_receta_calendario'];
        $comida = $_POST['asignar_comida_calendario'];
        crear_receta_calendario($con , $paciente , $dia , $receta , $comida);
        header('Location:nutricionista.php#div_calendario');
        exit;
    }
  }

//formulario para seleccionar el paciente y obtener el calendario
if(isset($_POST['ver_calendario'])){
    if(!empty($_POST['calendario_paciente'])){
        $usuario = $_POST['calendario_paciente'];
        $menu = obtener_calendario($con , $usuario);
        $_SESSION['menu'] = $menu; 
        header('Location:nutricionista.php#div_calendario');
        exit;
    }
  }
  
//Tabla con los pacientes
    echo '<div id="div_pacientes">
          <div id="contenedor_tabla_paciente" class="seccion">
          <div id="tabla_paciente">
          <h2>Listado de pacientes</h2>';
    $resultado = obtener_pacientes_nutricionista($con);
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
        echo "</div>";

    if(isset($_SESSION['mensaje_pacientes'])){
        echo $_SESSION['mensaje_pacientes'];
    }
        echo  "</div>";

    //Buscador pacientes
    echo '<div id="buscador_paciente" class="seccion">
    <form action="nutricionista.php#buscador_paciente" method="POST">
    <h2>Buscador de información sobre el paciente</h2>
    <h3>Introduzca el apellido completo o la inicial</h3>
    <input type="text" name="apellido_paciente_buscar" id="apellido_paciente_buscar" required><br/>
    <input type="submit" name="buscar_paciente">
    </form>';
    if (isset($_POST['buscar_paciente'])) {
        if (!empty($_POST['apellido_paciente_buscar'])) {
            $busqueda = mysqli_real_escape_string($con, trim($_POST['apellido_paciente_buscar']));
            $resultado = buscar_paciente_nutricionista($con, $busqueda);
            
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
   echo "</div>"; 


//crear paciente
echo '<div id="crear_paciente" class="seccion">
        <form id="formulario_crear_paciente" action="nutricionista.php#div_pacientes" method="POST" enctype="multipart/form-data">
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
            <input type="submit" name="crear_paciente">
        </form>
        <div id="mensaje_error_crear_paciente" style="color: red; display: none;"></div>
        </div>';

//Modificar paciente
  echo '<div id="modificar_paciente" class="seccion">
        <form id="formulario_mod_paciente" action="nutricionista.php#div_pacientes" method="POST" enctype="multipart/form-data" >
            <h2>Modificación de pacientes</h2>
            <label for="busq_paciente">Elija un paciente para modificar sus datos:</label>
            <select name="busq_paciente" id="busq_paciente">';
              $resultado = listar_pacientes_nutricionista($con);
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                 while ($fila = mysqli_fetch_assoc($resultado)) {
                 $paciente = $fila['usuario'];
                 echo "<option value='$paciente'>$paciente</option>";
                }
            }else {
                echo '<option disabled>No hay pacientes disponibles</option>';
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
echo '<div id="borrar_paciente" class="seccion">
        <form action="nutricionista.php#div_pacientes" method="POST">
            <h3>Eliminación de pacientes</h3>
            <label for="borrar_paciente">Elija un paciente a eliminar:</label>
            <select name="borrar_paciente" id="borrar_paciente">';
              $resultado = listar_pacientes_nutricionista($con);
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                 while ($fila = mysqli_fetch_assoc($resultado)) {
                 $paciente = $fila['usuario'];
                 echo "<option value='$paciente'>$paciente</option>";
                }
            }else {
                echo '<option disabled>No hay pacientes disponibles</option>';
            }
      echo '</select>
            <input type="submit" name="eliminar_paciente" value="Eliminar paciente">
        </form>
        </div>';
      echo  '</div>';

 //Tabla de recetas
 echo '<div id="div_recetas" >
       <h2>Tus recetas (ración/450 grs)</h2>
       <div id="tabla_contenedor_recetas" class="seccion">
       <div id="tabla_recetas">';
        $resultado = listar_recetas_usuario($con);
        if(mysqli_num_rows($resultado)==0){
           echo "<h5>No hay recetas disponibles.</h5>";
        }else{
           echo "<table>
           <tr><th>Plato</th><th>calorias/racion</th><th>Ingredientes</th></tr>";
        while($fila = mysqli_fetch_array($resultado)){
            extract($fila);
           echo "<tr><td>$nombre</td><td>$calorias</td><td>$ingredientes</td></tr>";
        }
           echo "</table>";
    }
           echo "</div>";
if(isset($_SESSION['mensaje_receta'])){
    echo $_SESSION['mensaje_receta'];
}
echo "</div>";

//crear receta
echo '<div id="crear_receta" class="seccion">
        <form id="formulario_crear_receta" action="nutricionista.php#div_recetas" method="POST">
            <h2>Creación de recetas</h2>
            <label for="nombre_receta">Nombre :</label>
            <input type="text" name="nombre_receta" id="nombre_receta" required><br/>
            <label for="calorias_receta">Calorias por racion(450 grs):</label>
            <input type="text" name="calorias_receta" id="calorias_receta" required><br/>
            <label for="ingredientes_receta">Ingredientes :</label><br/>
            <textarea rows="8" cols="50" name="ingredientes_receta" 
            placeholder="Escribe aquí tu receta...." id="ingredientes_receta" required></textarea>
            <input type="submit" name="crear_receta" value="Crear receta">
        </form>
        <div id="mensaje_error_crear_receta" style="color: red; display: none;"></div>
        </div>';

//Modificar receta
echo '<div id="modificar_receta" class="seccion">
<form id="formulario_mod_receta" action="nutricionista.php#div_recetas" method="POST">
    <h2>Modificación de recetas</h2>
    <label for="busq_receta">Elija una receta a modificar:</label>
    <select name="busq_receta" id="busq_receta">';
      $resultado = listar_recetas_nutricionista($con);
      if ($resultado && mysqli_num_rows($resultado) > 0) {
         while ($fila = mysqli_fetch_assoc($resultado)) {
         $receta = $fila['nombre'];
         echo "<option value='$receta'>$receta</option>";
        }
    }else {
        echo '<option disabled>No hay recetas disponibles</option>';
    }
    echo '</select>
    <h4>Modifique los datos de la receta : </h4>
    <label for="nombre_receta_mod">Nombre :</label>
    <input type="text" name="nombre_receta_mod" id="nombre_receta_mod" required><br/>
    <label for="calorias_receta_mod">Calorias por racion(450 grs):</label>
    <input type="text" name="calorias_receta_mod" id="calorias_receta_mod" required><br/>
    <label for="ingredientes_receta_mod">Ingredientes :</label><br/>
    <textarea rows="8" cols="50" name="ingredientes_receta_mod" 
    placeholder="Escribe aquí tu receta...." id="ingredientes_receta_mod" required></textarea>
    <input type="submit" name="crear_receta_mod" value="Modificar receta">
</form>
<div id="mensaje_error_mod_receta" style="color: red; display: none;"></div>
</div>';

//Eliminar receta
echo '<div id="borrar_receta" class="seccion">
        <form action="nutricionista.php#div_recetas" method="POST">
            <h3>Eliminación de recetas</h3>
            <label for="borrar_receta">Elija una receta a eliminar:</label>
            <select name="borrar_receta" id="borrar_receta">';
              $resultado = listar_recetas_nutricionista($con);
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                 while ($fila = mysqli_fetch_assoc($resultado)) {
                 $receta = $fila['nombre'];
                 echo "<option value='$receta'>$receta</option>";
                }
            }else {
                echo '<option disabled>No hay recetas disponibles</option>';
            }
      echo '</select>
            <input type="submit" name="eliminar_receta" value="Eliminar receta">
        </form>
        </div>';
      echo   '</div>';

//Select para ver tabla con el calendario de recetas
 echo '<div id="div_calendario" >
       <div id="tabla_calendario" class="seccion">
       <form action="nutricionista.php#div_calendario" method="POST">
       <h2>Calendario de recetas</h2>
       <label for="calendario_paciente">Elija un paciente para ver su menu semanal:</label>
            <select name="calendario_paciente" id="calendario_paciente">';
              $resultado = listar_pacientes_nutricionista($con);
              if ($resultado && mysqli_num_rows($resultado) > 0) {
                 while ($fila = mysqli_fetch_assoc($resultado)) {
                 $paciente = $fila['usuario'];
                 echo "<option value='$paciente'>$paciente</option>";
                }
            } else {
                echo '<option disabled>No hay pacientes disponibles</option>';
            }
                   
            echo '</select>
                  <input type="submit" name="ver_calendario" value="Ver calendario">
                  </form>';

         if (isset($_SESSION['calendario']) && !empty($_SESSION['calendario'])) {
            $menu = $_SESSION['menu'] ?? [];  
        echo '<table>';
        if(!empty($_SESSION['calendario'])){
        echo   '<h2>Calendario de '.$_SESSION['calendario'].'</h2>
        <tr>
            <th>Comida</th>
            <th>Lunes</th>
            <th>Martes</th>
            <th>Miércoles</th>
            <th>Jueves</th>
            <th>Viernes</th>
            <th>Sábado</th>
            <th>Domingo</th>
        </tr>';
        $comidas = ['Desayuno', 'Almuerzo', 'Cena'];
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        foreach ($comidas as $comida) {
            echo "<tr>";
            echo "<th>$comida</th>";
            foreach ($dias as $dia) {
                echo "<td>" . ($menu[$dia][$comida] ?? 'No asignado') . "</td>";
            }
            echo "</tr>";
        }
    echo "</table>";
        }
    }  
    echo "</form>";
    
    if(isset($_SESSION['mensaje_calendario'])){
       echo $_SESSION['mensaje_calendario'];
        }
    echo "</div>";

//Asignar receta a paciente en el calendario
echo '<div id="asignar_calendario" class="seccion">
      <form action="nutricionista.php#div_calendario" method="POST">
       <h2>Asignación de recetas al calendario del paciente</h2>
       <label for="asignar_paciente_calendario">Elija un paciente:</label>
       <select name="asignar_paciente_calendario" id="asignar_paciente_calendario">';
         $resultado = listar_pacientes_nutricionista($con);
          if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
            $paciente = $fila['usuario'];
            echo "<option value='$paciente'>$paciente</option>";
         }
     }else {
        echo '<option disabled>No hay pacientes disponibles</option>';
    }

     echo '</select>
           <label for="asignar_receta_calendario">Elija una receta:</label>
           <select name="asignar_receta_calendario" id="asignar_receta_calendario">';
        $resultado = listar_recetas_nutricionista($con);
            if ($resultado && mysqli_num_rows($resultado) > 0) {
            while ($fila = mysqli_fetch_assoc($resultado)) {
            $receta = $fila['nombre'];
            echo "<option value='$receta'>$receta</option>";
         }
      }else {
                echo '<option disabled>No hay recetas disponibles</option>';
            }
    
     echo  '</select>
           <label for="asignar_dia_calendario">Elija un día de la semana:</label>
           <select name="asignar_dia_calendario" id="asignar_dia_calendario">
            <option value="Lunes">Lunes</option>
            <option value="Martes">Martes</option>
            <option value="Miércoles">Miércoles</option>
            <option value="Jueves">Jueves</option>
            <option value="Viernes">Viernes</option>
            <option value="Sábado">Sábado</option>
            <option value="Domingo">Domingo</option>
            </select>
            <label for="asignar_comida_calendario">Elija un día de la semana:</label>
            <select name="asignar_comida_calendario" id="asignar_comida_calendario">
            <option value="Desayuno">Desayuno</option>
            <option value="Almuerzo">Almuerzo</option>
            <option value="Cena">Cena</option>
            </select>
            <input type="submit" name="asignar_calendario" value="Asignar receta">   
          </form>
          </div>
        </div>';         

//tabla de citas por nutricionistas
echo '<div id="div_citas" >
      <h2>Tu listado de citas</h2>
      <div id="tabla_citas" class="seccion">
      <div id="tabla_cita">';
       $resultado = obtener_tabla_citas_nutricionista($con);
       if(mysqli_num_rows($resultado)==0){
         echo "<h5>No tienes citas disponibles.</h5>";
      }else{
         echo "<table>
      <tr><th>Fecha</th><th>Hora</th><th>Usuario</th><th>Nombre completo</th><th>Email</th></tr>";
       while($fila = mysqli_fetch_array($resultado)){
       extract($fila);
        echo "<tr><td>$fecha</td><td>$hora</td><td>$usuario</td><td>$nombre $apellido</td><td>$email</td></tr>";
       }
        echo "</table>";
       }
        echo "</div>";

if(isset($_SESSION['mensaje_cita'])){
    echo $_SESSION['mensaje_cita'];
}
echo "</div>";

//crear citas
echo '<div id="crear_cita" class="seccion">
<form action="nutricionista.php#div_citas" method="POST">
<h2>Creación de citas :</h2>
<label for="paciente_cita">Asigne el paciente:</label>
<select name="paciente_cita" id="paciente_cita">';
    $resultado = listar_pacientes_nutricionista($con);
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $paciente = $fila['usuario'];
            echo "<option value='$paciente'>$paciente</option>";
        }
    } else {
        echo '<option disabled>No hay pacientes disponibles</option>';
    }
echo '<label for="hora_cita" name="hora_cita">Hora de la cita (hh:mm):</label>
     <input type="time" name="hora_cita"><br/>
     <label for="date" name="fecha_cita">Fecha de la cita (aaaa-mm-dd):</label>
     <input type="date" name="fecha_cita"><br/>
     <input type="submit" name="crear_cita" value="Crear cita">
     </form>
     </div>';



//Borrar citas
echo '<div id="borrar_cita" class="seccion">
<form action="nutricionista.php#borrar_cita" method="POST">
<h2>Cancelación de citas :</h2>
<label for="paciente_cita_borrar">Elija el paciente :</label>
<select name="paciente_cita_borrar" id="paciente_cita_borrar">';
    $resultado = listar_pacientes_nutricionista($con);
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $paciente = $fila['usuario'];
            echo "<option value='$paciente'>$paciente</option>";
        }
    } else {
        echo '<option disabled>No hay pacientes disponibles</option>';
    }
echo '</select><br/>';
echo '<label for="borrar_hora_cita" name="borrar_hora_cita">Hora de la cita (hh:mm):</label>
     <input type="time" name="borrar_hora_cita"><br/>
     <label for="date" name="borrar_fecha_cita">Fecha de la cita (aaaa-mm-dd):</label>
     <input type="date" name="borrar_fecha_cita"><br/>
     <input type="submit" name="borrar_cita" value="Borrar cita">';

    echo '</form>
           </div>
           </div>';
?> 

  <div id="boton_logout">   
    <button id="cerrarSesion">Cerrar sesión</button>
   </div>

</body>
<?php require("../html/footer.html"); ?>

</html>