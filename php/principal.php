<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/principal.css">
    <title>Document</title>
</head>
<body>
    <div class="principal">
        <img src="https://res.cloudinary.com/dup8qzlzv/image/upload/v1742377568/portada_ap7bfr.png" alt="">
    </div>
    <?php
    // Incluye el header adecuado según si la variable $_SESSION['usuario'] tiene contenido
    if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
        require("header_alta.php");
    } else {
        require("../html/header.html");
    }
        require("noticias_total.php");
        require("../html/footer.html");
    ?>
</body>
</html>