<?php
require("conexion.php");

$con = conexion();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../CSS/contacto2.css">
  <title>Contacto - Plataforma para Nutricionistas</title>
</head>
<body>

    <h2>Contáctanos</h2>
    <h3>Estamos aquí para ayudarte. Completa el formulario y nos pondremos en contacto contigo.</h3>

    <div class="container">
        <section class="form-section">
            <form action="#" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required />

                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required />

                <label for="opcion">Tipo de Nutricionista</label>
                <select id="opciones" name="opciones">
                <option value="">Seleccione una opción</option>
                    <?php
                        $especialidades = tipo_nutricionista($con);
                        foreach ($especialidades as $especialidad) {
                            echo $especialidad;
                        }                    
                    ?>
                </select>

                <label for="nutri">Nutricionista</label>
                <select id="nutricionistas" name="nutricionistas">
                    <option value="">Seleccione una opción</option>
                    <?php
                        $nutricionistas = nombre_nutricionista($con);
                        foreach ($nutricionistas as $nutricionista) {
                            echo $nutricionista;
                        }                    
                    ?>
                </select>

                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" rows="4" required></textarea>

                <button type="submit">Enviar</button>
            </form>
        </section>

        
    </div>

</body>
</html>
