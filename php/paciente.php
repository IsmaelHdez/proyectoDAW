<?php
session_start();
include 'conexion.php';

// Asegurar que solo los pacientes puedan acceder
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 2) {
    header("Location: index.php");
    exit();
}

$con = conexion();
$usuario = $_SESSION["usuario"];

// ----------------------------------------  REFERENTE A FICHA PACIENTE  -------------------------------------------------------//
$datos_paciente = ver_datos_paciente($con, $usuario);


// Manejo de actualización de datos del paciente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modificar"])){ 
    $nuevo_nombre = $_POST["nombre"];
    $nuevo_apellido = $_POST["apellido"];
    $nuevo_email = $_POST["email"];
    $nueva_pass = !empty($_POST["pass"]) ? password_hash($_POST["pass"], PASSWORD_DEFAULT) : null;
    
    modificar_datos_paciente($con, $nuevo_nombre, $nuevo_apellido, $nuevo_email, $usuario, $nueva_pass);
    
    header("Location: paciente.php");
    exit();
}

// ----------------------------------------  REFERENTE A MEDIDAS PACIENTE  -------------------------------------------------------//
$medidas_paciente = obtener_medidas_paciente($con, $usuario);

// Manejo de añadir medición de paciente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["añadir_medida"])) {
    $nueva_fecha = $_POST['fecha'];
    $nueva_altura = $_POST['altura'];
    $nuevo_peso = $_POST['peso'];
    $nueva_grasa = $_POST['grasa_corporal'];
    $nuevo_musculo = $_POST['musculo'];
    
    introducir_medidas($con, $usuario, $nueva_fecha, $nueva_altura, $nuevo_peso, $nueva_grasa, $nuevo_musculo);

    header("Location: paciente.php");
    exit();
}




// ----------------------------------------  REFERENTE A MENÚ SEMANAL -------------------------------------------------------//
$menu_semanal = mostrar_menu_semanal($con, $usuario);
$comidas = ['Desayuno', 'Almuerzo', 'Cena'];
$dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

$menu_estructurado = [];

//
foreach ($menu_semanal as $fila) {
    $menu_estructurado[$fila['dia_semana']][$fila['comida']] = [
        'plato' => $fila['plato'],
        'ingredientes' => $fila['ingredientes'],
        'calorias' => $fila['calorias']
    ];
}

// ----------------------------------------  REFERENTE A GESTIÓN DE CITAS  -------------------------------------------------------//
$citas = mostrar_citas_paciente($con, $usuario);

?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paciente</title>
    <link rel="stylesheet" href="../CSS/paciente.css">
    <script src="../JS/paciente.js" defer></script>
    
</head>
<body>
    <div class="contenedor">
        <nav class="menu-lateral">
            <button onclick="mostrarSeccion('ficha_paciente')">Ficha de Paciente</button>
            <button onclick="mostrarSeccion('mediciones_paciente')">Medidas Corporales</button>
            <button onclick="mostrarSeccion('menu_semanal')">Menú Semanal</button>
            <button onclick="mostrarSeccion('gestion_citas')">Gestión de Citas</button>
            <button onclick="mostrarSeccion('cerrar_sesion')">Cerrar Sesión</button>
        </nav>

        <div class="contenido">
            <div id="ficha_paciente" class="seccion">
                <h2>Ficha del Paciente</h2>
                <table border="1">
                    <tr><th>Dato</th><th>Valor</th></tr>
                    <tr><td>Nombre</td><td><?php echo htmlspecialchars($datos_paciente['nombre']); ?></td></tr>
                    <tr><td>Apellido</td><td><?php echo htmlspecialchars($datos_paciente['apellido']); ?></td></tr>
                    <tr><td>Email</td><td><?php echo htmlspecialchars($datos_paciente['email']); ?></td></tr>
                    <tr><td>Usuario</td><td><?php echo htmlspecialchars($datos_paciente['usuario']); ?></td></tr>
                    <tr><td>Contraseña</td><td>********</td></tr>
                    <tr><td>Nutricionista</td><td><?php echo htmlspecialchars($datos_paciente['nombre_nutricionista']); ?></td></tr>
                </table>
                <button onclick="mostrarFormulario()" style="text-align: right; margin-top: 10px;">Modificar Datos</button>
                
                <div id="formulario_modificar_ficha">
                    <h3>Modificar Datos</h3>
                    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <label>Nombre:</label>
                        <input type="text" name="nombre" value="<?php echo htmlspecialchars($datos_paciente['nombre']); ?>" required>
                        <label>Apellido:</label>
                        <input type="text" name="apellido" value="<?php echo htmlspecialchars($datos_paciente['apellido']); ?>" required>
                        <label>Email:</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($datos_paciente['email']); ?>" required>
                        <label>Nueva Contraseña (opcional):</label>
                        <input type="password" name="pass">
                        <button type="submit" name="modificar">Guardar Cambios</button>
                    </form>
                </div>
            </div>

            <div id="mediciones_paciente" class="seccion">
                <h2>Mediciones del Paciente</h2>
                <table border="1">
                    <thead>                   
                        <tr>
                            <th>Fecha Registro</th>
                            <th>Altura (cm)</th>
                            <th>Peso (kg)</th>
                            <th>Grasa Corporal (%)</th>
                            <th>Músculo (%)</th>
                            <th>IMC</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php          
                            foreach ($medidas_paciente as $medida) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($medida['fecha_registro']) . "</td>";
                            echo "<td>" . htmlspecialchars($medida['altura']) . "</td>";
                            echo "<td>" . htmlspecialchars($medida['peso']) . "</td>";
                            echo "<td>" . htmlspecialchars($medida['grasa_corporal']) . "</td>";
                            echo "<td>" . htmlspecialchars($medida['musculo']) . "</td>";
                            echo "<td>" . htmlspecialchars($medida['imc']) . "</td>";
                            echo "<td>
                                <button onclick='modificarMedicion()'>Modificar</button>
                                <button onclick='eliminarMedicion()'>Eliminar</button>
                                </td>";
                            echo "</tr>";
                        }?>
                    </tbody>
                </table>      
                <button onclick="añadirMedicion()">Añadir</button>

                <div id="formulario_añadir_medicion">
                    <h3>Añadir Medicion</h3>
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                        <label for="fecha">Fecha:</label>
                        <input type="date" id="fecha" name="fecha" required><br>
                        <label for="altura">Altura (cm):</label>
                        <input type="number" id="altura" name="altura" step="0.01" required><br>
                        <label for="peso">Peso (kg):</label>
                        <input type="number" id="peso" name="peso" step="0.01" required><br>
                        <label for="grasa">Grasa Corporal (%):</label>
                        <input type="number" id="grasa" name="grasa_corporal" step="0.01" required><br>                    
                        <label for="musculo">Músculo (%):</label>
                        <input type="number" id="musculo" name="musculo" step="0.01" required><br>
                    <button type="submit" name="añadir_medida">Guardar</button>
                    </form>
                </div>
            </div>

            <div id="menu_semanal" class="seccion">
                <h2>Menú Semanal</h2>
                <table border="1">
                    <thead>                    
                        <tr>
                            <th>Comida</th>
                            <?php foreach ($dias as $dia) echo "<th>$dia</th>"; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comidas as $comida) { ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($comida); ?></strong></td>
                                <?php foreach ($dias as $dia) { 
                                    if (isset($menu_estructurado[$dia][$comida])) { 
                                        $plato = htmlspecialchars($menu_estructurado[$dia][$comida]['plato']);
                                        $ingredientes = nl2br(htmlspecialchars($menu_estructurado[$dia][$comida]['ingredientes']));
                                        $calorias = htmlspecialchars($menu_estructurado[$dia][$comida]['calorias']);
                                ?>
                                        <td>
                                            <strong><?php echo $plato; ?></strong><br>
                                            <small>Ingredientes: <?php echo $ingredientes; ?></small><br>
                                            <small>Calorías: <?php echo $calorias; ?> kcal</small>
                                        </td>
                                <?php } else { echo "<td>-</td>"; } } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>   
            </div>


            <div id="gestion_citas" class="seccion">
                <h2>Gestión de Citas</h2>
                <table border="1">
                    <thead>                    
                        <tr>
                            <th>Día</th>
                            <th>Hora</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if (!empty($citas)) {
                                foreach ($citas as $cita) {
                                    echo "<tr>";
                                    echo "<td>" . $cita['fecha'] . "</td>";
                                    echo "<td>" . $cita['hora'] . "</td>";
                                    echo "<td>
                                            <button onclick=\"modificarCita('" . $cita['fecha'] . "', '" . $cita['hora'] . "')\">Modificar</button>
                                            <button onclick=\"borrarCita('" . $cita['fecha'] . "', '" . $cita['hora'] . "')\">Eliminar</button>
                                        </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No tienes citas registradas.</td></tr>";
                            }                        
                        ?>                                
                    </tbody>
                </table>
                <button id="nueva_cita" style="margin-top: 10px;">Sacar nueva cita</button>
            </div>
        </div>
    </div>
</body>
</html>
