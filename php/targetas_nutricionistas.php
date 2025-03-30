<?php
require("conexion.php");

$con = conexion();

$nutricionistas = obtener_nutricionista($con);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutricionistas</title>
    <link rel="stylesheet" href="../CSS/targetas_nutricionistas.css">
</head>
<body>
    <div class="titulo_nutricionistas">
            <h3>Nuestros ultimos colaboradores</h3>
        </div>
        <div class="colaboradores">
            <div class="card-container">
                <?php foreach ($nutricionistas as $nutricionista): ?>
                    <div class="card">
                        <h2><?= htmlspecialchars($nutricionista['nombre'] . " " . $nutricionista['apellido']) ?></h2>
                        <p><strong>Email:</strong> <?= htmlspecialchars($nutricionista['email']) ?></p>
                        <p><strong>Tipo:</strong> <?= $nutricionista['tipo'] == 1 ? "Nutricionista" : "General" ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
</body>
</html>
