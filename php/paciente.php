<?php
require("conexion.php");
$con = conexion();

// Verifica si no se ha iniciado una sesión; si no, la inicia
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$paciente = obtener_datos_paciente($con);

echo '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Paciente</title>
</head>
<body>
    <h1>Bienvenido, '.htmlspecialchars($paciente["nombre"]." ".$paciente["apellido"]).'</h1>
    <p>Email: ' . htmlspecialchars($paciente["email"]) . '</p>

    <h2>Actualizar Datos</h2>';

if (isset($_POST["actualizar"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    $pass = $_POST["pass"] ? password_hash($_POST["pass"], PASSWORD_DEFAULT) : $paciente["pass"];
    
    $sql = "UPDATE paciente SET nombre='$nombre', apellido='$apellido', email='$email', pass='$pass' WHERE usuario='" . $_SESSION["usuario"] . "'";
    mysqli_query($con, $sql);
    header("Location: paciente.php");
    exit;
}

echo '<form action="paciente.php" method="POST">
        <input type="text" name="nombre" value="' . htmlspecialchars($paciente["nombre"]) . '" required>
        <input type="text" name="apellido" value="' . htmlspecialchars($paciente["apellido"]) . '" required>
        <input type="email" name="email" value="' . htmlspecialchars($paciente["email"]) . '" required>
        <input type="password" name="pass" placeholder="Nueva contraseña (opcional)">
        <button type="submit" name="actualizar">Actualizar</button>
    </form>

    <h2>Registrar Medidas</h2>';

if (isset($_POST["registrar_medidas"])) {
    $altura = $_POST["altura"];
    $peso = $_POST["peso"];
    $grasa = $_POST["grasa"];
    $musculo = $_POST["musculo"];
    
    $sql = "INSERT INTO medidas_paciente (id_paciente, altura, peso, grasa_corporal, musculo) VALUES ('" . $paciente["id_paciente"] . "', '$altura', '$peso', '$grasa', '$musculo')";
    mysqli_query($con, $sql);
    header("Location: paciente.php");
    exit;
}

echo '<form action="paciente.php" method="POST">
        <input type="number" step="0.01" name="altura" placeholder="Altura (m)" required>
        <input type="number" step="0.1" name="peso" placeholder="Peso (kg)" required>
        <input type="number" step="0.1" name="grasa" placeholder="Grasa Corporal (%)">
        <input type="number" step="0.1" name="musculo" placeholder="Músculo (%)">
        <button type="submit" name="registrar_medidas">Registrar</button>
    </form>
</body>
</html>';
?>
