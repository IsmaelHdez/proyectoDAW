<?php
require_once("conexion.php");

$con = conexion();

$noticias = obtener_noticias($con);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrigo</title>
    <link rel="stylesheet" href="../CSS/principal2.css">
</head>
<body>
    <div class="secciones">
        <div class="seccion_noticia">
            <div class="titulo_noticias">
                <h3>Noticias</h3>
            </div> 
        <div class="noticias">
            <?php foreach ($noticias as $noticia): ?>
                <div class="noticias1">
                    <a href="noticias.php?url=<?= urlencode($noticia['noticia']) ?>">
                        <img src="<?= $noticia['foto']?>" alt="">
                        <h5><?= htmlspecialchars($noticia['titulo']) ?></h5>
                        <p><?= htmlspecialchars($noticia['subtitulo']) ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>