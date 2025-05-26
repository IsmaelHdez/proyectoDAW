<?php
session_start();
require("conexion.php");
require("header_paciente.php");


// Asegurar que solo los pacientes puedan acceder
if (!isset($_SESSION["tipo"]) || $_SESSION["tipo"] != 2) {
    header("Location: index.php");
    exit();
}

// Incluye el header adecuado según si la variable $_SESSION['usuario'] tiene contenido
if (isset($_COOKIE['token']) && isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    $token = $_COOKIE['token'];
    $usuario = $_SESSION['usuario'];
    $tipo = $_SESSION['tipo'];
    validar_token($token, $usuario, $tipo);
}

$con = conexion();
$usuario = $_SESSION["usuario"];

// ----------------------------------------  REFERENTE A FICHA PACIENTE  -------------------------------------------------------//
$datos_paciente = ver_datos_paciente($con, $usuario);


// Manejo de actualización de datos del paciente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["modificar"])) {
    $nuevo_nombre = $_POST["nombre"];
    $nuevo_apellido = $_POST["apellido"];
    $nuevo_email = $_POST["email"];
    $nueva_pass = !empty($_POST["pass"]) ? password_hash($_POST["pass"], PASSWORD_DEFAULT) : null;
    $nueva_foto = null;

    if (isset($_FILES["nueva_foto"]) && $_FILES["nueva_foto"]["size"] > 0) {
        if (!empty($datos_paciente["foto"])) {
            eliminar_imagen_cloudinary($datos_paciente["foto"]);
        }

        $nueva_foto = subir_imagen_cloudinary($_FILES["nueva_foto"]["tmp_name"]);
    }

    modificar_datos_paciente($con, $nuevo_nombre, $nuevo_apellido, $nuevo_email, $usuario, $nueva_pass, $nueva_foto);

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


// Manejo de eliminar cita existente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["eliminar_cita"])) {
    $fecha = $_POST["fecha"];
    $hora = $_POST["hora"];
    borrar_cita_paciente($con, $usuario, $fecha, $hora);
    header("Location: paciente.php");
    exit();
}


?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrigo</title>
    <link rel="stylesheet" href="../CSS/paciente.css">
    <script src="../JS/paciente.js" defer></script>
    <script src="../JS/logout.js" defer></script>        
</head>

<body>

        <div class="contenido">
            <div id="ficha_paciente" class="seccion active" style="display:block;">
                <h2>Ficha del Paciente</h2>

                <div class="perfil_contenedor">
                    <div class="perfil_foto">
                        <?php if (!empty($datos_paciente["foto"])): ?>
                            <img src="<?= $datos_paciente["foto"] ?>" alt="Foto de perfil" width="150" height="150">
                        <?php else: ?>
                            <p>No tienes foto de perfil.</p>
                        <?php endif; ?>
                    </div>

                    <div class="perfil_datos">
                        <table border="1">
                            <tr><th>Dato</th><th>Valor</th></tr>
                            <tr><td>Nombre</td><td><?= htmlspecialchars($datos_paciente['nombre']) ?></td></tr>
                            <tr><td>Apellido</td><td><?= htmlspecialchars($datos_paciente['apellido']) ?></td></tr>
                            <tr><td>Email</td><td><?= htmlspecialchars($datos_paciente['email']) ?></td></tr>
                            <tr><td>Usuario</td><td><?= htmlspecialchars($datos_paciente['usuario']) ?></td></tr>
                            <tr><td>Contraseña</td><td>********</td></tr>
                            <tr><td>Nutricionista</td><td><?= htmlspecialchars($datos_paciente['nombre_nutricionista']) ?></td></tr>
                        </table>

                        <button onclick="mostrarFormulario()" class="modificar-btn">Modificar Datos</button>

                        <div id="formulario_modificar_ficha" style="display: none;">
                            
                            <form action="paciente.php" method="POST" enctype="multipart/form-data">
                                <label for="nombre">Nombre:</label>
                                <input type="text" name="nombre" value="<?= $datos_paciente['nombre'] ?>" required>

                                <label for="apellido">Apellido:</label>
                                <input type="text" name="apellido" value="<?= $datos_paciente['apellido'] ?>" required>

                                <label for="email">Correo Electrónico:</label>
                                <input type="email" name="email" value="<?= $datos_paciente['email'] ?>" required>

                                <label for="pass">Nueva Contraseña (opcional):</label>
                                <input type="password" name="pass">

                                <label for="nueva_foto">Foto de perfil:</label>
                                <input type="file" name="nueva_foto" accept="image/*">

                                <input type="submit" name="modificar" value="Guardar Cambios" class="modificar-btn">
                            </form>
                        </div>
                    </div>
                </div>     

            </div>

            <div id="mediciones_paciente" class="seccion">
                <h2>Mediciones del Paciente</h2>
                <table border="1">
                    <thead>                   
                        <tr>
                            <th>Fecha Registro</th>
                            <th>Altura (m)</th>
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
                                <button class='accion-tbn' onclick='eliminarMedicion()'>Eliminar</button>
                                </td>";
                            echo "</tr>";
                        }?>
                    </tbody>
                </table>      
                <button class="boton-añadir" onclick="añadirMedicion()">Añadir</button>

                <div id="formulario_añadir_medicion" style="display: none;">
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
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($citas)): ?>
                            <?php foreach ($citas as $cita): ?>
                                <form method="POST">
                                    <tr>
                                        <td>
                                            <input type="date" name="fecha" value="<?= htmlspecialchars($cita['fecha']) ?>" required>
                                        </td>
                                        <td>
                                            <input type="time" name="hora" value="<?= htmlspecialchars($cita['hora']) ?>" required>
                                        </td>
                                        <td>
                                            <input type="hidden" name="id_cita" value="<?= htmlspecialchars($cita['id_citas']) ?>">
                                            <button type="submit" name="eliminar_cita" onclick="return confirm('¿Eliminar esta cita?')">Eliminar</button>
                                        </td>
                                    </tr>
                                </form>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3">No tienes citas registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
<?php require("../html/footer.html"); ?>
</html>

