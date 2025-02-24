<?php
require("conexion.php");

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*if($_SESSION["tipo"] != 3){
    header("Location: index.php");
}*/

//función que obtiene lista de nutricionistas
function obtener_nutricionistas($con){
    $resultado = mysqli_query($con,"select usuario , nombre , apellido , email from usuario where tipo = 1");
    return $resultado;
}

//funcion que busca nutricionista por apellido
function buscar_nutricionista($con,$apellido){
    $resultado = mysqli_query($con,"select usuario , nombre , apellido , email from usuario where tipo = 1 and apellido like '$apellido%'");
    return $resultado;
}

//función que obtiene lista de pacientes
function obtener_pacientes($con){
    $resultado = mysqli_query($con,"select usuario , nombre , apellido , email from usuario where tipo = 2");
    return $resultado;
}

//función que obtiene lista de recetas
function obtener_recetas($con){
    $resultado = mysqli_query($con,"select nombre, ingredientes, calorias from receta");
    return $resultado;
}

//función que obtiene lista de recetas
function buscar_recetas($con, $opcion){
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

//Buscador nutricionistas
   echo '<div id="buscador_nutricionista">
        <form action="admin.php" method="POST">
            <h3>Buscador de nutricionistas por apellidos</h3>
            <h4>Introduzca el apellido completo o la inicial</h4>
            <input type="text" name="apellido_nutricionista" id="apellido_nutricionista" required><br/>
            <input type="submit" name="buscar_nutricionista">
        </form>
        </div>';

    if (isset($_POST['buscar_nutricionista'])) {
        if (!empty($_POST['apellido_nutricionista'])) {
            $busqueda = mysqli_real_escape_string($con, trim($_POST['apellido']));
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
    
//tabla con los pacientes
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

//Tabla de recetas
  echo "<h2>Recetas (ración/450 grs)</h2>";
  $resultado = obtener_recetas($con);
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
          var_dump($busqueda);
          $resultado = buscar_recetas($con, $busqueda);
  
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
?>