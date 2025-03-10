<?php

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require("conexion.php");
$con = conexion();
$_SESSION['id_nutricionista'] =(int) obtener_datos_nutricionista($con);
var_dump($_SESSION['id_nutricionista']);

/*************************FUNCIONES DE NUTRICIONISTA.PHP********************************************** */
//función para ver ficha de paciente
function obtener_datos_nutricionista ($con){
    $usuario = $_SESSION['usuario'];
    //$resultado = mysqli_query ($con, "select id_nutricionista FROM nutricionista WHERE usuario = '$usuario'");
    $resultado = mysqli_query ($con, "select id_nutricionista FROM nutricionista WHERE usuario = 'caro'");
    if ($row = mysqli_fetch_assoc($resultado)) {
        return $row['id_nutricionista'];
    } else {
        return null;
    }
}

//función que obtiene lista de recetas
function listar_recetas_usuario($con){
    $id_nutri = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta where id_nutricionista = '$id_nutri';");
    return $resultado;
}

//funcion para crear receta
function crear_receta($con, $nombre_receta, $ingredientes_receta, $calorias_receta){
    $id_nutri = $_SESSION['id_nutricionista'];
    $resultado = mysqli_query($con, "insert into receta (nombre, ingredientes, calorias,id_nutricionista) values ('$nombre_receta','$ingredientes_receta', '$calorias_receta', '$id_nutri');");
    if (!$resultado) {
        unset($_SESSION['mensaje_receta']);
        echo "Error al crear la receta: " . mysqli_error($con);
    }
    
    $_SESSION['mensaje_receta'] = "<h5 class='mensaje'>Se ha creado la receta </h5><h5> con nombre : $nombre_receta .</h5>";
    }
/************************************************************************************************ */
/*if($_SESSION["tipo"] != 1){
    header("Location: index.php");
}*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/logout.js" defer></script>
    <link rel="stylesheet" href="../CSS/admin.css">
    <title>Document</title>
</head>
<body>
<?php

//formulario para crear paciente
if (isset($_POST['crear_paciente'])) {
    if (!empty($_POST['usuario_paciente']) && !empty($_POST['pass_paciente']) && !empty($_POST['nombre_paciente']) && !empty($_POST['apellido_paciente']) && !empty($_POST['email_paciente'])) {
        $usuario_paciente = $_POST['usuario_paciente'];
        $pass_paciente = $_POST['pass_paciente'];
        $nombre_paciente = $_POST['nombre_paciente'];
        $apellido_paciente = $_POST['apellido_paciente'];
        $email_paciente = $_POST['email_paciente'];
        $resultado = crear_paciente($con, $nombre_paciente , $apellido_paciente , $usuario_paciente , $pass_paciente ,  $email_paciente);
        header('Location:nutricionista.php#div_pacientes');
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
            $resultado = crear_receta($con, $nombre_receta, $ingredientes_receta , $calorias_receta );
            header('Location:nutricionista.php#div_recetas');
            exit;
    
        } 
    }
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
    <form action="nutricionista.php#buscador_paciente" method="POST">
    <h2>Buscador de paciente por apellido</h2>
    <h3>Introduzca el apellido completo o la inicial</h3>
    <input type="text" name="apellido_paciente_buscar" id="apellido_paciente" required><br/>
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
        <form action="nutricionista.php#div_pacientes" method="POST">
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
            <input type="submit" name="crear_paciente">
        </form>
        <div id="mensaje_error_crear_paciente" style="color: red; display: none;"></div>
        </div>';

//Modificar paciente
  echo '<div id="modificar_paciente">
        <form action="nutricionista.php#div_pacientes" method="POST">
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
echo '<div id="borrar_paciente">
        <form action="nutricionista.php#div_pacientes" method="POST">
            <h3>Eliminación de pacientes</h3>
            <label for="borrar_paciente">Elija un paciente para asignar la receta anterior:</label>
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
 <h2>Tus recetas (ración/450 grs)</h2>';
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
if(isset($_SESSION['mensaje_receta'])){
    echo $_SESSION['mensaje_receta'];
}
//crear receta
echo '<div id="crear_receta">
        <form action="nutricionista.php#div_recetas" method="POST">
            <h2>Creación de recetas</h2>
            <label for="nombre_receta">Nombre :</label>
            <input type="text" name="nombre_receta" id="nombre_receta" required><br/>
            <label for="calorias_receta">Calorias por racion(450 grs):</label>
            <input type="text" name="calorias_receta" id="calorias_receta" required><br/>
            <label for="ingredientes_receta">Ingredientes :</label>
            <textarea rows="8" cols="50" name="ingredientes_receta" 
            placeholder="Escribe aquí tu receta...." required></textarea>
            <input type="submit" name="crear_receta" value="Crear receta">
        </form>
        <div id="mensaje_error_crear_receta" style="color: red; display: none;"></div>
        </div>';
echo "</div>"; 
?> 

  <div id="boton_logout">   
    <button id="cerrarSesion">Cerrar sesión</button>
   </div>

</body>
</html>